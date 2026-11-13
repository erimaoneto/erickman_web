@extends('layouts.admin')

@section('title', 'Tulis Email Resmi')
@section('page_title', 'Modul Email: Kirim Email Resmi (SMTP Hostinger)')

@section('content')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">Tulis & Kirim Email Resmi</h2>
                    <p class="text-xs text-slate-500">Email akan dikirim melalui server SMTP resmi domain erickman.co.id.</p>
                </div>
                <a href="{{ route('admin.email.inbox') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kotak Masuk
                </a>
            </div>

            <!-- Active Sender Badge -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        <i class="fa-solid fa-at"></i>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[11px]">Akun Pengirim Resmi (SMTP Hostinger):</span>
                        <span class="font-bold text-navy-900">{{ config('mail.from.address', 'finance@erickman.co.id') }} <span class="text-slate-500 font-normal">({{ config('mail.from.name', 'PT. Erickman Sarana Abadi') }})</span></span>
                    </div>
                </div>
                <a href="https://mail.hostinger.com" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-[11px] uppercase tracking-wider transition flex items-center gap-1.5 self-start sm:self-auto shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square text-amber-600"></i>
                    <span>Webmail Hostinger</span>
                </a>
            </div>

            <form action="{{ route('admin.email.send') }}" method="POST" class="space-y-4">
                @csrf
                @if(isset($inquiryId))
                <input type="hidden" name="inquiry_id" value="{{ $inquiryId }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alamat Email Tujuan (To) *</label>
                        <input type="email" name="to_email" value="{{ old('to_email', $replyTo) }}" required placeholder="klien@perusahaan.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Penerima</label>
                        <input type="text" name="to_name" value="{{ old('to_name', $replyName) }}" placeholder="Contoh: Bpk. Budi" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Subjek Email *</label>
                    <input type="text" name="subject" value="{{ old('subject', $subject) }}" required placeholder="Penawaran Resmi Kerjasama Pengadaan Gas CNG - PT Erickman" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Isi Pesan Email *</label>
                    <textarea name="body" rows="10" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm leading-relaxed">{{ old('body', "Kepada Yth.\n" . ($replyName ? $replyName : 'Bapak/Ibu Pimpinan') . ",\n\nTerima kasih atas kepercayaan dan pertanyaan Anda kepada PT Erickman.\n\nSehubungan dengan permintaan penawaran yang Anda ajukan, bersama ini kami sampaikan bahwa tim operasional kami siap mendukung kebutuhan transportasi armada dan distribusi energi gas alam untuk perusahaan Anda.\n\nSilakan informasikan jadwal temu atau koordinasi lanjutan yang sesuai.\n\nHormat kami,\nManagement PT Erickman\n18 Office Park Building, 12th Floor, Jakarta Selatan\nWebsite: https://erickman.co.id") }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">
                        <i class="fa-solid fa-shield-halved text-brand-600 mr-1"></i> Terenkripsi SSL / TLS Hostinger SMTP
                    </span>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.email.outbox') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Email Sekarang</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
