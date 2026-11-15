@extends('layouts.admin')

@section('title', 'Detail Email - ' . $email->subject)
@section('page_title', 'Modul Email: Baca Pesan Hostinger')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Navigation & Action Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('admin.email.inbox', ['tab' => 'hostinger']) }}" class="text-xs font-bold text-slate-500 hover:text-navy-900 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Kotak Masuk</span>
            </a>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.email.compose', ['to' => $email->from_email, 'name' => $email->from_name, 'subject' => $email->subject]) }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-reply"></i>
                    <span>Balas Email Ini</span>
                </a>

                <form action="{{ route('admin.email.hostinger.delete', $email->msgno) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus email ini dari server Hostinger?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Email Reader Container -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden divide-y divide-slate-100">
            <!-- Email Metadata Header -->
            <div class="p-6 sm:p-8 space-y-4 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <h1 class="text-lg sm:text-xl font-bold text-navy-900 leading-snug">
                        {{ $email->subject }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex-shrink-0 self-start">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>Hostinger SSL IMAP</span>
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white font-bold flex items-center justify-center text-sm shadow-sm shadow-amber-500/20">
                            {{ substr($email->from_name, 0, 1) }}
                        </div>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-navy-900 text-sm">{{ $email->from_name }}</span>
                                <span class="text-slate-400 font-mono text-[11px]">&lt;{{ $email->from_email }}&gt;</span>
                            </div>
                            <div class="text-[11px] text-slate-500">
                                Kepada: <span class="font-medium text-slate-700">{{ $email->to_email ?: 'finance@erickman.co.id' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-left sm:text-right">
                        <div class="font-semibold text-slate-700">{{ $email->date->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</div>
                        <div class="text-[11px] text-slate-400">{{ $email->date->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Email Body Content -->
            <div class="p-6 sm:p-8">
                @if(!empty($email->html_body))
                <!-- HTML Email rendered safely in sandboxed iframe -->
                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-white shadow-inner">
                    <iframe srcdoc="{{ htmlspecialchars($email->html_body, ENT_QUOTES, 'UTF-8') }}" sandbox="allow-same-origin allow-popups" class="w-full min-h-[500px] border-0" onload="this.style.height = (this.contentWindow.document.body.scrollHeight + 40) + 'px';"></iframe>
                </div>
                @elseif(!empty($email->plain_body))
                <!-- Plain Text Email -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 text-sm leading-relaxed whitespace-pre-wrap font-sans selection:bg-brand-100">
{{ trim($email->plain_body) }}
                </div>
                @else
                <div class="p-8 text-center text-xs text-slate-400 italic">
                    (Email ini tidak memiliki teks isi pesan)
                </div>
                @endif

                <!-- Attachments if any -->
                @if(!empty($email->attachments) && count($email->attachments) > 0)
                <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Lampiran Berkas ({{ count($email->attachments) }})</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($email->attachments as $att)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="fa-solid fa-paperclip text-slate-400"></i>
                                <span class="font-medium text-navy-900 truncate">{{ $att['filename'] }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 flex-shrink-0 ml-2">{{ $att['size'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Reply Card -->
            <div class="p-6 sm:p-8 bg-slate-50/70 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-reply text-brand-600"></i>
                        <h3 class="text-sm font-bold text-navy-900">Kirim Balasan Cepat (SMTP Hostinger)</h3>
                    </div>
                    <span class="text-[11px] text-slate-500">Pengirim: <strong>finance@erickman.co.id</strong></span>
                </div>

                <form action="{{ route('admin.email.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="to_email" value="{{ $email->from_email }}">
                    <input type="hidden" name="to_name" value="{{ $email->from_name }}">
                    <input type="hidden" name="subject" value="{{ str_starts_with(strtolower($email->subject), 're:') ? $email->subject : 'Re: ' . $email->subject }}">

                    <div>
                        <textarea name="body" rows="6" required placeholder="Tuliskan balasan resmi Anda di sini..." class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm leading-relaxed bg-white shadow-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">
                            <i class="fa-solid fa-lock text-brand-600 mr-1"></i> Terenkripsi SSL Hostinger
                        </span>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Balasan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
