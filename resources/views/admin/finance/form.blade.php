@extends('layouts.admin')

@section('title', isset($transaction) ? 'Edit Transaksi ' . $transaction->code : 'Catat Transaksi Keuangan')
@section('page_title', isset($transaction) ? 'Edit Transaksi Keuangan: ' . $transaction->code : 'Input Transaksi Keuangan')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">{{ isset($transaction) ? 'Edit Transaksi ' . $transaction->code : 'Catat Pemasukan / Pengeluaran' }}</h2>
                    <p class="text-xs text-slate-500">
                        {{ isset($transaction) ? 'Perbarui rincian nominal, kategori, atau armada terkait.' : 'Catat mutasi kas operasional, tagihan klien, atau belanja armada.' }}
                    </p>
                </div>
                <a href="{{ route('admin.finance.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ isset($transaction) ? route('admin.finance.update', $transaction->id) : route('admin.finance.store') }}" method="POST" class="space-y-5" x-data="{ type: '{{ old('type', $transaction->type ?? 'pengeluaran') }}' }">
                @csrf
                @if(isset($transaction))
                    @method('PUT')
                @endif

                <!-- Type Selector Tabs -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Transaksi *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label @click="type = 'pemasukan'" :class="type === 'pemasukan' ? 'border-emerald-500 bg-emerald-50/70 text-emerald-800' : 'border-slate-200 text-slate-600'" class="p-3.5 rounded-2xl border-2 flex items-center justify-center gap-2 cursor-pointer font-bold text-sm transition">
                            <input type="radio" name="type" value="pemasukan" x-model="type" class="sr-only">
                            <i class="fa-solid fa-arrow-down text-emerald-600"></i>
                            <span>Pemasukan (Inflow)</span>
                        </label>
                        <label @click="type = 'pengeluaran'" :class="type === 'pengeluaran' ? 'border-rose-500 bg-rose-50/70 text-rose-800' : 'border-slate-200 text-slate-600'" class="p-3.5 rounded-2xl border-2 flex items-center justify-center gap-2 cursor-pointer font-bold text-sm transition">
                            <input type="radio" name="type" value="pengeluaran" x-model="type" class="sr-only">
                            <i class="fa-solid fa-arrow-up text-rose-600"></i>
                            <span>Pengeluaran (Outflow)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kategori Transaksi *</label>
                        <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <template x-if="type === 'pemasukan'">
                                <optgroup label="Kategori Pemasukan">
                                    @php $currentCat = old('category', $transaction->category ?? ''); @endphp
                                    <option value="Distribusi Gas CNG" {{ $currentCat == 'Distribusi Gas CNG' ? 'selected' : '' }}>Distribusi Gas CNG</option>
                                    <option value="Sewa Truk" {{ $currentCat == 'Sewa Truk' ? 'selected' : '' }}>Sewa Truk / Armada</option>
                                    <option value="Penjualan Gas & Bahan Bakar" {{ $currentCat == 'Penjualan Gas & Bahan Bakar' ? 'selected' : '' }}>Penjualan Gas & Bahan Bakar</option>
                                    <option value="Jasa Kontrak Logistik" {{ $currentCat == 'Jasa Kontrak Logistik' ? 'selected' : '' }}>Jasa Kontrak Logistik</option>
                                    <option value="Lain-lain" {{ $currentCat == 'Lain-lain' ? 'selected' : '' }}>Lain-lain (Pemasukan)</option>
                                </optgroup>
                            </template>
                            <template x-if="type === 'pengeluaran'">
                                <optgroup label="Kategori Pengeluaran">
                                    @php $currentCat = old('category', $transaction->category ?? ''); @endphp
                                    <option value="BBM & Bahan Bakar" {{ $currentCat == 'BBM & Bahan Bakar' ? 'selected' : '' }}>BBM (Solar/Gas Industri)</option>
                                    <option value="Uang Jalan Supir" {{ $currentCat == 'Uang Jalan Supir' ? 'selected' : '' }}>Uang Jalan Supir & Tol</option>
                                    <option value="Maintenance Armada" {{ $currentCat == 'Maintenance Armada' ? 'selected' : '' }}>Maintenance & Sparepart Truk</option>
                                    <option value="Gaji & Upah" {{ $currentCat == 'Gaji & Upah' ? 'selected' : '' }}>Gaji & Upah Personel</option>
                                    <option value="Pajak & Legalitas (KIR/STNK)" {{ $currentCat == 'Pajak & Legalitas (KIR/STNK)' ? 'selected' : '' }}>Pajak & Biaya Uji KIR/STNK</option>
                                    <option value="Operasional Kantor" {{ $currentCat == 'Operasional Kantor' ? 'selected' : '' }}>Operasional Kantor 18 Office Park</option>
                                    <option value="Lain-lain" {{ $currentCat == 'Lain-lain' ? 'selected' : '' }}>Lain-lain (Pengeluaran)</option>
                                </optgroup>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nominal Transaksi (Rp) *</label>
                        <input type="number" name="amount" value="{{ old('amount', isset($transaction) ? (int)$transaction->amount : '') }}" required placeholder="Contoh: 15000000" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Transaksi *</label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', isset($transaction) ? $transaction->transaction_date->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Terkait Armada Truk (Opsional)</label>
                        <select name="fleet_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <option value="">-- Tidak Terkait Armada Khusus --</option>
                            @foreach($fleets as $f)
                            <option value="{{ $f->id }}" {{ old('fleet_id', $transaction->fleet_id ?? '') == $f->id ? 'selected' : '' }}>
                                {{ $f->plate_number }} - {{ $f->vehicle_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Referensi / Invoice / Nota</label>
                    <input type="text" name="reference_invoice" value="{{ old('reference_invoice', $transaction->reference_invoice ?? '') }}" placeholder="Contoh: INV-ERK-2026-088 atau SPBU-1290" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Keterangan / Rincian Transaksi</label>
                    <textarea name="description" rows="3" placeholder="Rincian pengeluaran/pemasukan, nama klien, rute pengantaran..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">{{ old('description', $transaction->description ?? '') }}</textarea>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.finance.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">
                        {{ isset($transaction) ? 'Simpan Perubahan Transaksi' : 'Simpan Transaksi Keuangan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
