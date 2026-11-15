@extends('layouts.admin')

@section('title', 'Kotak Masuk Email')
@section('page_title', 'Modul Email: Kotak Masuk & Korespondensi')

@section('content')

    <div class="space-y-6">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Kotak Masuk Website & Email Hostinger</h2>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-xs text-slate-500">Akses langsung email resmi domain erickman.co.id & pesan formulir penawaran web.</p>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Hostinger Mail: finance@erickman.co.id
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.email.inbox', ['tab' => $tab]) }}" class="px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-sm" title="Perbarui / Segarkan Kotak Masuk">
                    <i class="fa-solid fa-arrows-rotate text-brand-600"></i>
                    <span>Segarkan</span>
                </a>
                <a href="{{ route('admin.email.compose') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Tulis Email (SMTP)</span>
                </a>
            </div>
        </div>

        <!-- Tab Navigation & Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.email.inbox', ['tab' => 'hostinger']) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $tab === 'hostinger' ? 'bg-navy-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-envelope-open-text text-amber-400"></i>
                    <span>Email Hostinger (finance@erickman.co.id)</span>
                    @if(isset($hostingerUnread) && $hostingerUnread > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">{{ $hostingerUnread }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.email.inbox', ['tab' => 'inquiries']) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $tab === 'inquiries' ? 'bg-navy-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-file-contract text-brand-500"></i>
                    <span>Formulir Website (Inquiries)</span>
                    @if($webUnreadCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-600 text-white">{{ $webUnreadCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.email.inbox') }}" method="GET" class="w-full md:w-80 flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari subjek / pengirim..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
                @if(request('search'))
                <a href="{{ route('admin.email.inbox', ['tab' => $tab]) }}" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold" title="Reset Pencarian">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </form>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        <!-- TAB CONTENT: HOSTINGER LIVE INBOX -->
        @if($tab === 'hostinger')
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-xs text-navy-900 uppercase tracking-wider">Kotak Masuk Langsung (IMAP Hostinger)</span>
                    <span class="text-[11px] text-slate-500">• Terenkripsi SSL Port 993</span>
                </div>
                <span class="text-[11px] text-slate-500">Klik surat untuk membuka dan membaca isi pesan langsung di sini</span>
            </div>

            @if(isset($hostingerEmails) && $hostingerEmails->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($hostingerEmails as $mail)
                <div class="p-4 sm:p-5 hover:bg-slate-50 transition flex flex-col md:flex-row md:items-center justify-between gap-4 {{ !$mail->is_seen ? 'bg-amber-50/30' : '' }}">
                    <a href="{{ route('admin.email.hostinger.show', $mail->msgno) }}" class="flex items-start gap-3.5 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm flex-shrink-0 {{ !$mail->is_seen ? 'bg-amber-500 text-white font-bold shadow-sm shadow-amber-500/20' : 'bg-slate-100 text-slate-500' }}">
                            <i class="fa-solid {{ !$mail->is_seen ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                        </div>
                        <div class="space-y-1 min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-navy-900 truncate max-w-xs">{{ $mail->from_name }}</span>
                                @if(!$mail->is_seen)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">Baru</span>
                                @endif
                                <span class="text-[11px] text-slate-400 font-mono truncate">&lt;{{ $mail->from_email }}&gt;</span>
                            </div>
                            <div class="font-medium text-xs text-slate-800 truncate">
                                {{ $mail->subject }}
                            </div>
                        </div>
                    </a>

                    <div class="flex items-center justify-between md:justify-end gap-3 flex-shrink-0">
                        <div class="text-right">
                            <div class="text-xs font-semibold text-slate-600">{{ $mail->date->isoFormat('D MMM Y, HH:mm') }} WIB</div>
                            <div class="text-[10px] text-slate-400">{{ $mail->date->diffForHumans() }}</div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.email.hostinger.show', $mail->msgno) }}" class="px-3 py-1.5 rounded-lg bg-navy-900 hover:bg-navy-800 text-white text-xs font-bold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span>Buka</span>
                            </a>
                            <form action="{{ route('admin.email.hostinger.delete', $mail->msgno) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus email ini dari server Hostinger?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Email">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($hostingerEmails->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $hostingerEmails->links() }}
            </div>
            @endif

            @else
            <div class="p-12 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-envelope-open"></i>
                </div>
                <h3 class="text-sm font-bold text-navy-900">Kotak Masuk Hostinger Kosong</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    @if(request('search'))
                    Tidak ditemukan email dengan kata kunci "{{ request('search') }}". Silakan reset pencarian.
                    @else
                    Belum ada email baru yang masuk ke akun finance@erickman.co.id. Klik tombol "Segarkan" untuk memeriksa ulang.
                    @endif
                </p>
            </div>
            @endif
        </div>
        @endif

        <!-- TAB CONTENT: WEB INQUIRIES -->
        @if($tab === 'inquiries')
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <span class="font-bold text-xs text-navy-900 uppercase tracking-wider">Permintaan Penawaran dari Formulir Web</span>
                <span class="text-[11px] text-slate-500">Tersimpan di database website</span>
            </div>

            @if($inquiries->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($inquiries as $inq)
                <div class="p-4 sm:p-5 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ !$inq->is_read ? 'bg-brand-50/40' : '' }}">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm flex-shrink-0 {{ !$inq->is_read ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 text-slate-500' }}">
                            {{ substr($inq->name, 0, 1) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-navy-900">{{ $inq->name }}</span>
                                @if(!$inq->is_read)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">Baru</span>
                                @endif
                                @if($inq->replied_at)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                    <i class="fa-solid fa-reply text-[9px]"></i> Sudah Dibalas
                                </span>
                                @endif
                                @if($inq->company)
                                <span class="text-xs text-slate-500 font-medium">• {{ $inq->company }}</span>
                                @endif
                            </div>
                            <div class="font-medium text-xs text-slate-700">
                                {{ $inq->subject ?: '(Permintaan Penawaran Umum)' }}
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-1">
                                {{ $inq->message }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0">
                        <div class="text-right">
                            <div class="text-xs font-semibold text-slate-600">{{ $inq->created_at->isoFormat('D MMM Y, HH:mm') }} WIB</div>
                            <div class="text-[10px] text-slate-400">{{ $inq->created_at->diffForHumans() }}</div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.email.show', $inq->id) }}" class="px-3 py-1.5 rounded-lg bg-navy-900 hover:bg-navy-800 text-white text-xs font-bold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span>Buka</span>
                            </a>
                            <form action="{{ route('admin.email.delete', $inq->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($inquiries->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $inquiries->links() }}
            </div>
            @endif

            @else
            <div class="p-12 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <h3 class="text-sm font-bold text-navy-900">Belum Ada Pesan Formulir</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Pesan pertanyaan dan permintaan penawaran yang dikirim melalui formulir kontak landing page akan muncul di sini.</p>
            </div>
            @endif
        </div>
        @endif

    </div>

@endsection
