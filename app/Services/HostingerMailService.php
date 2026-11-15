<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Throwable;

class HostingerMailService
{
    protected ?string $host;
    protected int $port;
    protected string $encryption;
    protected ?string $username;
    protected ?string $password;

    public function __construct()
    {
        $this->host = config('mail.imap.host', 'imap.hostinger.com');
        $this->port = (int) config('mail.imap.port', 993);
        $this->encryption = config('mail.imap.encryption', 'ssl');
        $this->username = config('mail.mailers.smtp.username');
        $this->password = config('mail.mailers.smtp.password');
    }

    /**
     * Check if IMAP is configured and available.
     */
    public function isAvailable(): bool
    {
        return function_exists('imap_open') && !empty($this->username) && !empty($this->password);
    }

    /**
     * Open an IMAP stream.
     */
    protected function openMailbox(string $folder = 'INBOX')
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $mailboxStr = "{" . $this->host . ":" . $this->port . "/imap/" . $this->encryption . "/novalidate-cert}" . $folder;

        // Suppress warnings with @ to handle connection errors gracefully
        return @imap_open($mailboxStr, $this->username, $this->password, 0, 1, [
            'DISABLE_AUTHENTICATOR' => 'GSSAPI'
        ]);
    }

    /**
     * Get paginated inbox messages with search and sorting.
     */
    public function getInboxMessages(int $page = 1, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        if (!$this->isAvailable()) {
            // In environments without imap (e.g. local unit tests), return empty or mock paginator
            return new LengthAwarePaginator([], 0, $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $mbox = $this->openMailbox('INBOX');
        if (!$mbox) {
            return new LengthAwarePaginator([], 0, $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        try {
            $totalMessages = imap_num_msg($mbox);
            if ($totalMessages === 0) {
                imap_close($mbox);
                return new LengthAwarePaginator([], 0, $perPage, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]);
            }

            // Fetch overview for all messages
            $overview = imap_fetch_overview($mbox, "1:{$totalMessages}", 0);
            if (!is_array($overview)) {
                $overview = [];
            }

            // Sort newest first (highest msgno first)
            usort($overview, function ($a, $b) {
                $timeA = isset($a->udate) ? (int)$a->udate : (isset($a->date) ? strtotime($a->date) : 0);
                $timeB = isset($b->udate) ? (int)$b->udate : (isset($b->date) ? strtotime($b->date) : 0);
                if ($timeA === $timeB) {
                    return $b->msgno <=> $a->msgno;
                }
                return $timeB <=> $timeA;
            });

            $items = collect($overview)->map(function ($msg) {
                $subject = !empty($msg->subject) ? $this->decodeMimeHeader($msg->subject) : '(Tanpa Subjek)';
                $from = !empty($msg->from) ? $this->decodeMimeHeader($msg->from) : 'Tidak Diketahui';
                
                // Parse sender email and name
                $fromName = $from;
                $fromEmail = '';
                if (preg_match('/^(.*?)\s*<([^>]+)>$/u', $from, $matches)) {
                    $fromName = trim($matches[1], ' "\'');
                    $fromEmail = trim($matches[2]);
                } elseif (filter_var($from, FILTER_VALIDATE_EMAIL)) {
                    $fromEmail = $from;
                    $fromName = explode('@', $from)[0];
                }

                $date = isset($msg->date) ? Carbon::parse($msg->date) : Carbon::now();
                $isSeen = !empty($msg->seen);

                return (object) [
                    'msgno' => $msg->msgno,
                    'uid' => $msg->uid ?? $msg->msgno,
                    'subject' => $subject,
                    'from' => $from,
                    'from_name' => $fromName ?: $fromEmail,
                    'from_email' => $fromEmail ?: $fromName,
                    'date' => $date,
                    'is_seen' => $isSeen,
                    'size' => isset($msg->size) ? $this->formatBytes($msg->size) : null,
                ];
            });

            // Filter by search query if present
            if (!empty($search)) {
                $searchLower = mb_strtolower($search);
                $items = $items->filter(function ($item) use ($searchLower) {
                    return str_contains(mb_strtolower($item->subject), $searchLower)
                        || str_contains(mb_strtolower($item->from_name), $searchLower)
                        || str_contains(mb_strtolower($item->from_email), $searchLower);
                });
            }

            $total = $items->count();
            $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();

            imap_close($mbox);

            return new LengthAwarePaginator($slice, $total, $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        } catch (Throwable $e) {
            @imap_close($mbox);
            return new LengthAwarePaginator([], 0, $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }
    }

    /**
     * Get single message detail by message number.
     */
    public function getMessageDetail(int $msgno): ?object
    {
        if (!$this->isAvailable()) {
            return null;
        }

        $mbox = $this->openMailbox('INBOX');
        if (!$mbox) {
            return null;
        }

        try {
            $header = @imap_headerinfo($mbox, $msgno);
            if (!$header) {
                imap_close($mbox);
                return null;
            }

            // Mark message as seen
            @imap_setflag_full($mbox, (string)$msgno, "\\Seen");

            $subject = !empty($header->subject) ? $this->decodeMimeHeader($header->subject) : '(Tanpa Subjek)';
            
            // From extraction
            $fromName = '';
            $fromEmail = '';
            if (!empty($header->from[0])) {
                $fromObj = $header->from[0];
                $fromEmail = ($fromObj->mailbox ?? '') . '@' . ($fromObj->host ?? '');
                $fromName = !empty($fromObj->personal) ? $this->decodeMimeHeader($fromObj->personal) : $fromEmail;
            }

            // To extraction
            $toEmail = '';
            if (!empty($header->to[0])) {
                $toObj = $header->to[0];
                $toEmail = ($toObj->mailbox ?? '') . '@' . ($toObj->host ?? '');
            }

            // Body extraction
            $bodyData = $this->extractMessageBody($mbox, $msgno);

            $date = isset($header->date) ? Carbon::parse($header->date) : Carbon::now();

            $message = (object) [
                'msgno' => $msgno,
                'uid' => @imap_uid($mbox, $msgno) ?: $msgno,
                'subject' => $subject,
                'from_name' => $fromName,
                'from_email' => $fromEmail,
                'to_email' => $toEmail,
                'date' => $date,
                'html_body' => $bodyData['html'] ?? null,
                'plain_body' => $bodyData['plain'] ?? null,
                'attachments' => $bodyData['attachments'] ?? [],
            ];

            imap_close($mbox);
            return $message;
        } catch (Throwable $e) {
            @imap_close($mbox);
            return null;
        }
    }

    /**
     * Delete an email from Hostinger inbox.
     */
    public function deleteMessage(int $msgno): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $mbox = $this->openMailbox('INBOX');
        if (!$mbox) {
            return false;
        }

        try {
            $res = @imap_delete($mbox, $msgno);
            @imap_expunge($mbox);
            imap_close($mbox);
            return (bool) $res;
        } catch (Throwable $e) {
            @imap_close($mbox);
            return false;
        }
    }

    /**
     * Get unread messages count.
     */
    public function getUnreadCount(): int
    {
        if (!$this->isAvailable()) {
            return 0;
        }

        $mbox = $this->openMailbox('INBOX');
        if (!$mbox) {
            return 0;
        }

        try {
            $status = @imap_status($mbox, "{" . $this->host . ":" . $this->port . "/imap/" . $this->encryption . "/novalidate-cert}INBOX", SA_UNSEEN);
            $count = $status ? (int) $status->unseen : 0;
            imap_close($mbox);
            return $count;
        } catch (Throwable $e) {
            @imap_close($mbox);
            return 0;
        }
    }

    /**
     * Extract plain text and html body from message.
     */
    protected function extractMessageBody($mbox, int $msgno): array
    {
        $structure = @imap_fetchstructure($mbox, $msgno);
        $result = [
            'html' => null,
            'plain' => null,
            'attachments' => [],
        ];

        if (!$structure) {
            $rawBody = @imap_body($mbox, $msgno);
            $result['plain'] = $rawBody;
            return $result;
        }

        if (empty($structure->parts)) {
            // Single part
            $raw = @imap_body($mbox, $msgno);
            $decoded = $this->decodePartContent($raw, $structure->encoding);
            if (isset($structure->subtype) && strtoupper($structure->subtype) === 'HTML') {
                $result['html'] = $decoded;
            } else {
                $result['plain'] = $decoded;
            }
        } else {
            // Multipart
            $this->parseSubParts($mbox, $msgno, $structure->parts, '', $result);
        }

        return $result;
    }

    protected function parseSubParts($mbox, int $msgno, array $parts, string $partPrefix, array &$result): void
    {
        foreach ($parts as $index => $part) {
            $partNumber = $partPrefix ? $partPrefix . '.' . ($index + 1) : (string)($index + 1);

            // Check if it has subparts
            if (!empty($part->parts)) {
                $this->parseSubParts($mbox, $msgno, $part->parts, $partNumber, $result);
                continue;
            }

            // Check for attachment
            $filename = null;
            if ($part->ifdparameters) {
                foreach ($part->dparameters as $dparam) {
                    if (strtolower($dparam->attribute) === 'filename') {
                        $filename = $this->decodeMimeHeader($dparam->value);
                    }
                }
            }
            if (!$filename && $part->ifparameters) {
                foreach ($part->parameters as $param) {
                    if (strtolower($param->attribute) === 'name') {
                        $filename = $this->decodeMimeHeader($param->value);
                    }
                }
            }

            $raw = @imap_fetchbody($mbox, $msgno, $partNumber);
            $decoded = $this->decodePartContent($raw, $part->encoding);

            if ($filename) {
                $result['attachments'][] = [
                    'filename' => $filename,
                    'size' => isset($part->bytes) ? $this->formatBytes($part->bytes) : '0 KB',
                    'mime' => (isset($part->subtype) ? strtolower($part->subtype) : 'octet-stream'),
                ];
                continue;
            }

            $subtype = isset($part->subtype) ? strtoupper($part->subtype) : '';
            if ($subtype === 'HTML' && empty($result['html'])) {
                $result['html'] = $decoded;
            } elseif ($subtype === 'PLAIN' && empty($result['plain'])) {
                $result['plain'] = $decoded;
            }
        }
    }

    protected function decodePartContent(?string $content, int $encoding): string
    {
        if ($content === null) {
            return '';
        }

        switch ($encoding) {
            case 3: // Base64
                $decoded = base64_decode($content);
                break;
            case 4: // Quoted-Printable
                $decoded = quoted_printable_decode($content);
                break;
            default:
                $decoded = $content;
                break;
        }

        // Detect and convert encoding if necessary
        if (!mb_check_encoding($decoded, 'UTF-8')) {
            $decoded = mb_convert_encoding($decoded, 'UTF-8', 'ISO-8859-1');
        }

        return $decoded;
    }

    /**
     * Decode MIME encoded headers (e.g., =?UTF-8?B?...?=)
     */
    protected function decodeMimeHeader(string $text): string
    {
        if (function_exists('imap_mime_header_decode')) {
            $elements = @imap_mime_header_decode($text);
            if (is_array($elements)) {
                $decoded = '';
                foreach ($elements as $el) {
                    $charset = strtoupper($el->charset);
                    if ($charset === 'DEFAULT' || $charset === 'UTF-8') {
                        $decoded .= $el->text;
                    } else {
                        $decoded .= @mb_convert_encoding($el->text, 'UTF-8', $charset);
                    }
                }
                return $decoded;
            }
        } elseif (function_exists('iconv_mime_decode')) {
            return @iconv_mime_decode($text, 0, 'UTF-8');
        }

        return $text;
    }

    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
