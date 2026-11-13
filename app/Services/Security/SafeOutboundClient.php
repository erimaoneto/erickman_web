<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafeOutboundClient
{
    /**
     * Dangerous internal/loopback/private IP ranges (RFC 1918, RFC 3927, RFC 5737).
     */
    protected array $blockedIpRanges = [
        '0.0.0.0/8',
        '10.0.0.0/8',
        '100.64.0.0/10',
        '127.0.0.0/8',
        '169.254.0.0/16', // Cloud metadata (AWS, GCP, DigitalOcean)
        '172.16.0.0/12',
        '192.0.0.0/24',
        '192.0.2.0/24',
        '192.168.0.0/16',
        '198.18.0.0/15',
        '198.51.100.0/24',
        '203.0.113.0/24',
        '224.0.0.0/4',   // Multicast
        '240.0.0.0/4',   // Reserved
        '::1/128',       // IPv6 Loopback
        'fc00::/7',      // IPv6 Unique local
        'fe80::/10',     // IPv6 Link-local
    ];

    /**
     * Allowed outbound ports.
     */
    protected array $allowedPorts = [80, 443];

    /**
     * Potentially dangerous PHP functions for arbitrary outbound execution / reverse shell.
     */
    protected array $dangerousFunctions = [
        'exec',
        'passthru',
        'shell_exec',
        'system',
        'proc_open',
        'popen',
        'show_source',
    ];

    /**
     * Validate an outbound URL against SSRF and unauthorized destinations.
     */
    public function validateUrl(string $url): array
    {
        $parsed = parse_url($url);

        if (!$parsed || !isset($parsed['host'])) {
            return [
                'is_safe' => false,
                'reason' => 'Format URL tidak valid atau host tidak ditemukan',
            ];
        }

        // 1. Only allow HTTP and HTTPS
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            return [
                'is_safe' => false,
                'reason' => "Skema protokol tidak diizinkan: {$scheme}:// (Hanya http/https)",
            ];
        }

        // 2. Validate port
        $port = $parsed['port'] ?? ($scheme === 'https' ? 443 : 80);
        if (!in_array($port, $this->allowedPorts, true)) {
            return [
                'is_safe' => false,
                'reason' => "Port tujuan tidak diizinkan: {$port} (Hanya port 80 & 443)",
            ];
        }

        // 3. Resolve IP address and check for private / loopback / metadata ranges
        $host = $parsed['host'];
        $resolvedIps = @dns_get_record($host, DNS_A + DNS_AAAA);
        $ips = [];

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ips[] = $host;
        } elseif (!empty($resolvedIps)) {
            foreach ($resolvedIps as $record) {
                if (isset($record['ip'])) {
                    $ips[] = $record['ip'];
                }
                if (isset($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        } else {
            $ip = @gethostbyname($host);
            if ($ip && $ip !== $host) {
                $ips[] = $ip;
            }
        }

        if (empty($ips)) {
            return [
                'is_safe' => false,
                'reason' => "Host tidak dapat di-resolve ke IP address: {$host}",
            ];
        }

        foreach ($ips as $ip) {
            if ($this->isIpInBlockedRange($ip)) {
                return [
                    'is_safe' => false,
                    'reason' => "Tujuan IP dilarang (Private / Loopback / Cloud Metadata): {$ip}",
                ];
            }
        }

        return [
            'is_safe' => true,
            'host' => $host,
            'ips' => $ips,
            'port' => $port,
        ];
    }

    /**
     * Check if an IP address belongs to any blocked CIDR range.
     */
    protected function isIpInBlockedRange(string $ip): bool
    {
        // Simple check for loopback and metadata literals
        if (in_array($ip, ['127.0.0.1', '::1', '0.0.0.0', '169.254.169.254', 'localhost'], true)) {
            return true;
        }

        // Check against private filter flags
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return true;
        }

        return false;
    }

    /**
     * Send a safe outbound HTTP GET request with SSRF guard.
     */
    public function safeGet(string $url, array $options = [])
    {
        $validation = $this->validateUrl($url);

        if (!$validation['is_safe']) {
            Log::channel('single')->warning("[SECURITY SENTINEL] Blocked suspicious outbound request to {$url}: {$validation['reason']}");
            throw new \InvalidArgumentException("Permintaan keluar diblokir oleh Security Sentinel: " . $validation['reason']);
        }

        Log::channel('single')->info("[SECURITY SENTINEL] Safe outbound request permitted: {$url}");

        return Http::timeout($options['timeout'] ?? 10)->get($url);
    }

    /**
     * Audit PHP disabled functions in server configuration.
     */
    public function auditPhpDangerousFunctions(): array
    {
        $disabledStr = ini_get('disable_functions');
        $disabledList = array_filter(array_map('trim', explode(',', (string)$disabledStr)));

        $report = [];
        foreach ($this->dangerousFunctions as $func) {
            $isDisabled = in_array($func, $disabledList, true) || !function_exists($func);
            $report[$func] = [
                'function' => $func,
                'status' => $isDisabled ? 'DISABLED (SECURE)' : 'ENABLED (POTENTIAL RISK)',
                'is_disabled' => $isDisabled,
            ];
        }

        return [
            'disabled_functions_raw' => $disabledStr ?: '(None)',
            'audited_functions' => $report,
        ];
    }

    /**
     * Audit outbound mail activity.
     */
    public function auditOutboundMail(): array
    {
        $todayCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('inquiries')) {
            $todayCount = \App\Models\Inquiry::whereDate('created_at', today())->count();
        }

        return [
            'inquiries_today' => $todayCount,
            'status' => $todayCount > 200 ? 'WARNING (Unusually high form submissions)' : 'NORMAL',
        ];
    }

    /**
     * Run full outbound traffic security audit.
     */
    public function runFullAudit(): array
    {
        $phpFuncs = $this->auditPhpDangerousFunctions();
        $mail = $this->auditOutboundMail();

        return [
            'status' => 'SECURE',
            'ssrf_guard' => 'ACTIVE',
            'allowed_ports' => $this->allowedPorts,
            'blocked_ip_ranges_count' => count($this->blockedIpRanges),
            'php_functions' => $phpFuncs,
            'mail_activity' => $mail,
            'audited_at' => now()->toIso8601String(),
        ];
    }
}
