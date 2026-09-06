@extends('layouts.admin')

@section('title', 'Kelola Statistik & Metrik Utama')
@section('page_title', 'CMS: Statistik & Angka Counter')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Statistik & Metrik Pencapaian</h2>
                <p class="text-xs text-slate-500">Angka pencapaian dan rekor operasional yang tampil pada banner counter di bawah slider beranda.</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Angka Metrik</span>
            </button>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($metrics as $metric)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between" x-data="{ editModal: false }">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            #{{ $metric->order }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $metric->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $metric->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="text-3xl font-black {{ $metric->color_theme === 'brand' ? 'text-brand-600' : ($metric->color_theme === 'emerald' ? 'text-emerald-600' : ($metric->color_theme === 'sky' ? 'text-sky-600' : 'text-navy-900')) }}">
                        {{ $metric->number_value }}
                    </div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-2">
                        {{ $metric->label }}
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <button @click="editModal = true" class="font-bold text-slate-600 hover:text-brand-600 flex items-center gap-1 transition">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>

                    <form action="{{ route('admin.content.metrics.delete', $metric->id) }}" method="POST" onsubmit="return confirm('Hapus statistik ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-bold text-rose-600 hover:text-rose-800 flex items-center gap-1 transition">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal -->
                <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200" @click.away="editModal = false">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <h3 class="font-bold text-base text-navy-900">Edit Angka Statistik</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.content.metrics.update', $metric->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nilai / Angka *</label>
                                <input type="text" name="number_value" value="{{ $metric->number_value }}" required placeholder="Contoh: 50+ atau 99.4%" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan Label *</label>
                                <input type="text" name="label" value="{{ $metric->label }}" required placeholder="Contoh: Unit Armada Prima" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Angka</label>
                                    <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                        <option value="brand" {{ $metric->color_theme === 'brand' ? 'selected' : '' }}>Biru Brand</option>
                                        <option value="navy" {{ $metric->color_theme === 'navy' ? 'selected' : '' }}>Navy Gelap</option>
                                        <option value="emerald" {{ $metric->color_theme === 'emerald' ? 'selected' : '' }}>Hijau Emerald</option>
                                        <option value="sky" {{ $metric->color_theme === 'sky' ? 'selected' : '' }}>Biru Langit</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                    <input type="number" name="order" value="{{ $metric->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>
                            </div>

                            <div class="pt-2">
                                <label class="relative flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $metric->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                    <span class="text-xs font-bold text-slate-700">Aktifkan Tampilan</span>
                                </label>
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                Belum ada data metrik statistik.
            </div>
            @endforelse
        </div>

        <!-- Add Modal -->
        <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-base text-navy-900">Tambah Angka Statistik Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.content.metrics.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nilai / Angka *</label>
                        <input type="text" name="number_value" required placeholder="Contoh: 100+ atau 99.8%" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan Label *</label>
                        <input type="text" name="label" required placeholder="Contoh: Klien Korporat Aktif" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Angka</label>
                            <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                <option value="brand">Biru Brand</option>
                                <option value="navy">Navy Gelap</option>
                                <option value="emerald">Hijau Emerald</option>
                                <option value="sky">Biru Langit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ count($metrics) + 1 }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="relative flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            <span class="text-xs font-bold text-slate-700">Aktifkan Tampilan</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Tambah Metrik</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
