@extends('layouts.admin')

@section('title', 'Kelola Pilar Bisnis Tentang Kami')
@section('page_title', 'CMS: Pilar Bisnis & Sorotan Layanan')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Pilar Bisnis Utama (Tentang Kami)</h2>
                <p class="text-xs text-slate-500">Poin sorotan bisnis (seperti LPG HARIGAS, CNG, Transportasi) yang tampil pada seksi Tentang Kami.</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Pilar Bisnis</span>
            </button>
        </div>

        <!-- Pillar List Cards -->
        <div class="space-y-4">
            @forelse($pillars as $pillar)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row md:items-center justify-between gap-6" x-data="{ editModal: false }">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 {{ $pillar->color_theme === 'orange' ? 'bg-orange-100 text-orange-600' : ($pillar->color_theme === 'blue' ? 'bg-blue-100 text-blue-600' : ($pillar->color_theme === 'emerald' ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-100 text-brand-600')) }}">
                        <i class="{{ $pillar->icon }}"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Urutan #{{ $pillar->order }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $pillar->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $pillar->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-base text-navy-900">{{ $pillar->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed max-w-2xl">{{ $pillar->description }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-end md:self-center shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                    <button @click="editModal = true" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>

                    <form action="{{ route('admin.content.pillars.delete', $pillar->id) }}" method="POST" onsubmit="return confirm('Hapus pilar bisnis ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal -->
                <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
                    <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-2xl border border-slate-200" @click.away="editModal = false">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <h3 class="font-bold text-base text-navy-900">Edit Pilar Bisnis</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.content.pillars.update', $pillar->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Pilar *</label>
                                <input type="text" name="title" value="{{ $pillar->title }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat *</label>
                                <textarea name="description" rows="3" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">{{ $pillar->description }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                                    <input type="text" name="icon" value="{{ $pillar->icon }}" placeholder="fa-solid fa-fire-flame-simple" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tema Warna</label>
                                    <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                        <option value="orange" {{ $pillar->color_theme === 'orange' ? 'selected' : '' }}>Oranye</option>
                                        <option value="blue" {{ $pillar->color_theme === 'blue' ? 'selected' : '' }}>Biru</option>
                                        <option value="emerald" {{ $pillar->color_theme === 'emerald' ? 'selected' : '' }}>Hijau Emerald</option>
                                        <option value="brand" {{ $pillar->color_theme === 'brand' ? 'selected' : '' }}>Biru Brand</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                    <input type="number" name="order" value="{{ $pillar->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>

                                <div class="flex items-center pt-6">
                                    <label class="relative flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $pillar->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                        <span class="text-xs font-bold text-slate-700">Aktifkan Pilar</span>
                                    </label>
                                </div>
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
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                Belum ada pilar bisnis yang ditambahkan.
            </div>
            @endforelse
        </div>

        <!-- Add Modal -->
        <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-base text-navy-900">Tambah Pilar Bisnis Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.content.pillars.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Pilar *</label>
                        <input type="text" name="title" required placeholder="Contoh: Distributor Resmi Gas LPG (HARIGAS)" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat *</label>
                        <textarea name="description" rows="3" required placeholder="Jelaskan ruang lingkup atau jangkauan layanan ini..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                            <input type="text" name="icon" value="fa-solid fa-fire-flame-simple" placeholder="fa-solid fa-icon" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tema Warna</label>
                            <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                <option value="orange">Oranye</option>
                                <option value="blue">Biru</option>
                                <option value="emerald">Hijau Emerald</option>
                                <option value="brand">Biru Brand</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ count($pillars) + 1 }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Pilar</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Tambah Pilar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
