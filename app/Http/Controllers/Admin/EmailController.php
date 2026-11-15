<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\SentEmail;
use App\Services\HostingerMailService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Throwable;

class EmailController extends Controller
{
    // --- INBOX (Hostinger Webmail + Pesan Masuk Form Web) ---
    public function inbox(Request $request, HostingerMailService $mailService)
    {
        $tab = $request->get('tab', 'hostinger');
        $search = $request->get('search');
        $page = (int) $request->get('page', 1);

        // Hostinger Webmail IMAP Data
        $hostingerEmails = null;
        $hostingerUnread = 0;
        $isImapAvailable = $mailService->isAvailable();

        if ($isImapAvailable) {
            $hostingerUnread = $mailService->getUnreadCount();
            if ($tab === 'hostinger') {
                $hostingerEmails = $mailService->getInboxMessages($page, 15, $search);
            }
        }

        // Web Inquiries Data
        $inquiriesQuery = Inquiry::query();
        if ($request->filter == 'unread') {
            $inquiriesQuery->where('is_read', false);
        } elseif ($request->filter == 'replied') {
            $inquiriesQuery->whereNotNull('replied_at');
        }

        if ($request->filled('search') && $tab === 'inquiries') {
            $inquiriesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $inquiries = $inquiriesQuery->latest()->paginate(12);
        $webUnreadCount = Inquiry::where('is_read', false)->count();

        return view('admin.email.inbox', compact(
            'tab',
            'hostingerEmails',
            'hostingerUnread',
            'isImapAvailable',
            'inquiries',
            'webUnreadCount'
        ));
    }

    // --- HOSTINGER EMAIL DETAIL (Buka & Baca Email Langsung di Web) ---
    public function showHostingerEmail($msgno, HostingerMailService $mailService)
    {
        $email = $mailService->getMessageDetail((int) $msgno);

        if (!$email) {
            return redirect()->route('admin.email.inbox', ['tab' => 'hostinger'])
                ->with('warning', 'Email tidak ditemukan atau sesi koneksi IMAP telah berakhir.');
        }

        return view('admin.email.hostinger_show', compact('email'));
    }

    // --- HOSTINGER EMAIL DELETE ---
    public function deleteHostingerEmail($msgno, HostingerMailService $mailService)
    {
        $deleted = $mailService->deleteMessage((int) $msgno);

        if ($deleted) {
            return redirect()->route('admin.email.inbox', ['tab' => 'hostinger'])
                ->with('success', 'Email berhasil dihapus dari kotak masuk Hostinger.');
        }

        return redirect()->route('admin.email.inbox', ['tab' => 'hostinger'])
            ->with('warning', 'Email gagal dihapus atau server IMAP sedang sibuk.');
    }

    // --- WEB INQUIRY DETAIL ---
    public function showInquiry($id)
    {
        $inquiry = Inquiry::with('sentEmails')->findOrFail($id);

        if (!$inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return view('admin.email.show', compact('inquiry'));
    }

    public function deleteInquiry($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('admin.email.inbox', ['tab' => 'inquiries'])->with('success', 'Pesan penawaran berhasil dihapus!');
    }

    // --- COMPOSE & SEND EMAIL ---
    public function compose(Request $request)
    {
        $replyTo = $request->get('to', '');
        $replyName = $request->get('name', '');
        $subject = $request->get('subject', '');
        $inquiryId = $request->get('inquiry_id', null);

        if ($subject && !str_starts_with(strtolower($subject), 're:')) {
            $subject = 'Re: ' . $subject;
        }

        return view('admin.email.compose', compact('replyTo', 'replyName', 'subject', 'inquiryId'));
    }

    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'to_email' => 'required|email|max:150',
            'to_name' => 'nullable|string|max:150',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
            'inquiry_id' => 'nullable|exists:inquiries,id',
        ]);

        $status = 'sent';
        $errorMessage = null;

        try {
            // Send email using Laravel Mail / Hostinger SMTP
            Mail::html(nl2br(e($validated['body'])), function ($message) use ($validated) {
                $message->to($validated['to_email'], $validated['to_name'] ?: null)
                        ->subject($validated['subject']);
            });
        } catch (Throwable $e) {
            // In case SMTP is not yet configured or in offline sandbox, log the error gracefully
            $status = 'failed';
            $errorMessage = $e->getMessage();
        }

        // Record in sent_emails table
        SentEmail::create([
            'to_email' => $validated['to_email'],
            'to_name' => $validated['to_name'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'status' => $status,
            'error_message' => $errorMessage,
            'inquiry_id' => $validated['inquiry_id'] ?? null,
            'sender_id' => Auth::id(),
            'sent_at' => Carbon::now(),
        ]);

        if (!empty($validated['inquiry_id'])) {
            Inquiry::where('id', $validated['inquiry_id'])->update(['replied_at' => Carbon::now()]);
        }

        if ($status === 'failed') {
            return redirect()->route('admin.email.outbox')->with('warning', 'Pesan disimpan di Log Email, namun pengiriman SMTP mengembalikan respon: ' . $errorMessage);
        }

        return redirect()->route('admin.email.outbox')->with('success', 'Email resmi berhasil dikirim ke ' . $validated['to_email'] . '!');
    }

    // --- OUTBOX / SENT LOG ---
    public function outbox(Request $request)
    {
        $sentEmails = SentEmail::with('sender')->latest('sent_at')->paginate(15);
        return view('admin.email.outbox', compact('sentEmails'));
    }
}
