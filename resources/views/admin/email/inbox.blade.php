@extends('layouts.admin')

@section('title', 'Kotak Masuk (Inbox)')
@section('page_title', 'Modul Email: Pesan & Permintaan Penawaran')

@section('content')

    <div class="space-y-6">
        <!-- Header & Compose Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Kotak Masuk Website (Inquiries)</h2>
                <p class="text-xs text-slate-500">Pesan permintaan penawaran dan pertanyaan yang dikirimkan oleh calon mitra melalui formulir kontak.</p>
            </div>
            <a href="{{ route('admin.email.compose') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Tulis Email Baru (SMTP)</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.email.inbox') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !request('filter') ? 'bg-navy-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Pesan
                </a>
                <a href="{{ route('admin.email.inbox', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('filter') == 'unread' ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Belum Dibaca ({{ $unreadCount }})
                </a>
                <a href="{{ route('admin.email.inbox', ['filter' => 'replied']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('filter') == 'replied' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Sudah Dibalas
                </a>
            </div>

            <form action="{{ route('admin.email.inbox') }}" method="GET" class="w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / subjek..." class="w-full px-3.5 py-1.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
            </form>
        </div>

        <!-- Inquiries List -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($inquiries as $inq)
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
                                <span class="text-xs text-slate-500">({{ $inq->company }})</span>
                                @endif
                            </div>
                            <h4 class="text-xs font-semibold text-slate-800">{{ $inq->subject }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ $inq->message }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-center">
                        <div class="text-right">
                            <span class="text-[11px] text-slate-400 block">{{ $inq->created_at->diffForHumans() }}</span>
                            @if($inq->service_interest)
                            <span class="text-[10px] text-brand-600 font-semibold block">{{ $inq->service_interest }}</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.email.show', $inq->id) }}" class="px-3.5 py-1.5 rounded-lg bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs transition">
                            Buka
                        </a>
                        <form action="{{ route('admin.email.delete', $inq->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center text-slate-400 text-xs">
                    Tidak ada pesan penawaran masuk saat ini.
                </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $inquiries->links() }}
            </div>
        </div>
    </div>

@endsection
