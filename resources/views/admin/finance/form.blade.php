@extends('layouts.admin')

@section('title', 'Catat Transaksi Keuangan')
@section('page_title', 'Input Transaksi Keuangan')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">Catat Pemasukan / Pengeluaran</h2>
                    <p class="text-xs text-slate-500">Catat mutasi kas operasional, tagihan klien, atau belanja armada.</p>
                </div>
                <a href="{{ route('admin.finance.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('admin.finance.store') }}" method="POST" class="space-y-5" x-data="{ type: 'pengeluaran' }">
                @csrf

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
                                    <option value="Distribusi Gas CNG">Distribusi Gas CNG</option>
                                    <option value="Sewa Truk">Sewa Truk / Armada</option>
                                    <option value="Penjualan Gas & Bahan Bakar">Penjualan Gas & Bahan Bakar</option>
                                    <option value="Jasa Kontrak Logistik">Jasa Kontrak Logistik</option>
                                    <option value="Lain-lain">Lain-lain (Pemasukan)</option>
                                </optgroup>
                            </template>
                            <template x-if="type === 'pengeluaran'">
                                <optgroup label="Kategori Pengeluaran">
                                    <option value="BBM & Bahan Bakar">BBM (Solar/Gas Industri)</option>
                                    <option value="Uang Jalan Supir">Uang Jalan Supir & Tol</option>
                                    <option value="Maintenance Armada">Maintenance & Sparepart Truk</option>
                                    <option value="Gaji & Upah">Gaji & Upah Personel</option>
                                    <option value="Pajak & Legalitas (KIR/STNK)">Pajak & Biaya Uji KIR/STNK</option>
                                    <option value="Operasional Kantor">Operasional Kantor 18 Office Park</option>
                                    <option value="Lain-lain">Lain-lain (Pengeluaran)</option>
                                </optgroup>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nominal Transaksi (Rp) *</label>
                        <input type="number" name="amount" required placeholder="Contoh: 15000000" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Transaksi *</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Terkait Armada Truk (Opsional)</label>
                        <select name="fleet_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <option value="">-- Tidak Terkait Armada Khusus --</option>
                            @foreach($fleets as $f)
                            <option value="{{ $f->id }}">{{ $f->plate_number }} - {{ $f->vehicle_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Referensi / Invoice / Nota</label>
                    <input type="text" name="reference_invoice" placeholder="Contoh: INV-ERK-2026-088 atau SPBU-1290" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Keterangan / Rincian Transaksi</label>
                    <textarea name="description" rows="3" placeholder="Rincian pengeluaran/pemasukan, nama klien, rute pengantaran..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.finance.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">
                        Simpan Transaksi Keuangan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
