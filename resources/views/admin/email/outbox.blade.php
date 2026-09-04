@extends('layouts.admin')

@section('title', 'Riwayat Email Terkirim')
@section('page_title', 'Modul Email: Riwayat Outbox (Terkirim)')

@section('content')

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Log Email Terkirim (Outbox)</h2>
                <p class="text-xs text-slate-500">Seluruh korespondensi email resmi yang dikirimkan dari sistem ke mitra dan klien.</p>
            </div>
            <a href="{{ route('admin.email.compose') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Tulis Email Baru</span>
            </a>
        </div>

        <!-- Table of Sent Emails -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Waktu Kirim</th>
                            <th class="py-3.5 px-4">Penerima (To)</th>
                            <th class="py-3.5 px-4">Subjek</th>
                            <th class="py-3.5 px-4">Status Pengiriman</th>
                            <th class="py-3.5 px-4">Pengirim (Admin)</th>
                            <th class="py-3.5 px-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sentEmails as $email)
                        <tr class="hover:bg-slate-50/80 transition" x-data="{ viewModal: false }">
                            <td class="py-3.5 px-4 font-mono text-slate-800">
                                {{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-navy-900 block">{{ $email->to_email }}</span>
                                @if($email->to_name)
                                <span class="text-[11px] text-slate-400">{{ $email->to_name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $email->subject }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($email->status === 'sent')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1 w-max">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terkirim Sukses
                                </span>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 flex items-center gap-1 w-max" title="{{ $email->error_message }}">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Gagal
                                </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $email->sender->name ?? 'Sistem' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button @click="viewModal = true" class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                    Lihat Pesan
                                </button>

                                <!-- View Modal -->
                                <div x-show="viewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm text-left">
                                    <div @click.away="viewModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-2xl">
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                            <h3 class="font-bold text-base text-navy-900">Salinan Email Terkirim</h3>
                                            <button @click="viewModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                        </div>

                                        <div class="space-y-2 text-xs">
                                            <div><strong>Kepada:</strong> {{ $email->to_email }} {{ $email->to_name ? '(' . $email->to_name . ')' : '' }}</div>
                                            <div><strong>Waktu:</strong> {{ $email->sent_at ? $email->sent_at->format('d F Y, H:i:s') : '-' }} WIB</div>
                                            <div><strong>Subjek:</strong> {{ $email->subject }}</div>
                                            @if($email->error_message)
                                            <div class="p-2.5 rounded bg-rose-50 border border-rose-200 text-rose-700">
                                                <strong>Log Error SMTP:</strong> {{ $email->error_message }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="pt-2">
                                            <span class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Isi Pesan:</span>
                                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                                                {{ $email->body }}
                                            </div>
                                        </div>

                                        <div class="pt-4 flex justify-end">
                                            <button @click="viewModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat pengiriman email.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $sentEmails->links() }}
            </div>
        </div>
    </div>

@endsection
