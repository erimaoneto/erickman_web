@extends('layouts.admin')

@section('title', 'Pesan dari ' . $inquiry->name)
@section('page_title', 'Rincian Pesan & Permintaan Penawaran')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Navigation & Reply Action -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.email.inbox') }}" class="text-xs font-semibold text-slate-600 hover:text-navy-900 flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Kotak Masuk
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.email.compose', ['to' => $inquiry->email, 'name' => $inquiry->name, 'subject' => $inquiry->subject, 'inquiry_id' => $inquiry->id]) }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-reply"></i>
                    <span>Balas via Email Resmi</span>
                </a>
            </div>
        </div>

        <!-- Inquiry Body Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <!-- Header of Message -->
            <div class="border-b border-slate-100 pb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-lg">
                        {{ substr($inquiry->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-lg text-navy-900">{{ $inquiry->name }}</h3>
                            @if($inquiry->company)
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded bg-slate-100 text-slate-700">{{ $inquiry->company }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                            <a href="mailto:{{ $inquiry->email }}" class="text-brand-600 font-semibold hover:underline">{{ $inquiry->email }}</a>
                            @if($inquiry->phone)
                            <span>&bull;</span>
                            <span>Telp/WA: <strong class="text-slate-700">{{ $inquiry->phone }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-right text-xs text-slate-400">
                    <span>{{ $inquiry->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    @if($inquiry->service_interest)
                    <div class="mt-1">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                            Minat: {{ $inquiry->service_interest }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Subject & Content -->
            <div class="space-y-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Subjek:</span>
                <h4 class="text-base font-bold text-navy-900">{{ $inquiry->subject }}</h4>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $inquiry->message }}
                </div>
            </div>

            <!-- Quick WhatsApp Action -->
            @if($inquiry->phone)
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-2xl"></i>
                    <div>
                        <h5 class="font-bold text-xs text-emerald-950">Respons Cepat via WhatsApp</h5>
                        <p class="text-[11px] text-emerald-800">Hubungi nomor telepon pengirim secara langsung.</p>
                    </div>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}?text=Halo%20Bpk/Ibu%20{{ urlencode($inquiry->name) }},%20kami%20dari%20PT%20Erickman%20menindaklanjuti%20permintaan%20penawaran%20Anda." target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                    Buka WhatsApp
                </a>
            </div>
            @endif
        </div>

        <!-- Replies History (If Any) -->
        @if($inquiry->sentEmails->count() > 0)
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <h4 class="font-bold text-base text-navy-900">Riwayat Balasan Email Resmi</h4>
            <div class="space-y-3">
                @foreach($inquiry->sentEmails as $reply)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">Subjek: {{ $reply->subject }}</span>
                        <span class="text-slate-400 text-[11px]">{{ $reply->sent_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>
                    <p class="text-slate-600 whitespace-pre-line">{{ $reply->body }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

@endsection
