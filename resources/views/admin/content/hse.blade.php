@extends('layouts.admin')

@section('title', 'Kelola Standar Keselamatan HSE / K3')
@section('page_title', 'CMS: Standar HSE & Keselamatan Operasi')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Standar Keselamatan (HSE / K3)</h2>
                <p class="text-xs text-slate-500">Kartu komitmen keselamatan dan kepatuhan prosedur migas yang tampil pada seksi Safety First (HSE).</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Standar HSE</span>
            </button>
        </div>

        <!-- HSE Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($hseItems as $hse)
            <div class="bg-navy-950 text-white rounded-2xl border border-slate-800 shadow-sm p-6 flex flex-col justify-between" x-data="{ editModal: false }">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl font-bold {{ $hse->color_theme === 'emerald' ? 'bg-emerald-600/20 text-emerald-400' : ($hse->color_theme === 'sky' ? 'bg-sky-600/20 text-sky-400' : ($hse->color_theme === 'red' ? 'bg-red-600/20 text-red-400' : 'bg-brand-600/20 text-brand-400')) }}">
                            <i class="{{ $hse->icon }}"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-400">#{{ $hse->order }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $hse->is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-700 text-slate-400' }}">
                                {{ $hse->is_active ? 'Aktif' : 'Off' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-base text-white leading-snug">{{ $hse->title }}</h3>
                        <p class="text-xs text-slate-400 leading-relaxed mt-2">{{ $hse->description }}</p>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-800 flex items-center justify-between text-xs">
                    <button @click="editModal = true" class="font-bold text-slate-300 hover:text-white flex items-center gap-1 transition">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>

                    <form action="{{ route('admin.content.hse.delete', $hse->id) }}" method="POST" onsubmit="return confirm('Hapus poin HSE ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-bold text-rose-400 hover:text-rose-300 flex items-center gap-1 transition">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal -->
                <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/80 backdrop-blur-sm text-slate-800" x-cloak>
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200" @click.away="editModal = false">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <h3 class="font-bold text-base text-navy-900">Edit Standar HSE</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.content.hse.update', $hse->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Protokol HSE *</label>
                                <input type="text" name="title" value="{{ $hse->title }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Protokol *</label>
                                <textarea name="description" rows="3" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">{{ $hse->description }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                                    <input type="text" name="icon" value="{{ $hse->icon }}" placeholder="fa-solid fa-shield-halved" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Aksen</label>
                                    <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                        <option value="brand" {{ $hse->color_theme === 'brand' ? 'selected' : '' }}>Biru Brand</option>
                                        <option value="emerald" {{ $hse->color_theme === 'emerald' ? 'selected' : '' }}>Hijau Emerald</option>
                                        <option value="sky" {{ $hse->color_theme === 'sky' ? 'selected' : '' }}>Biru Langit</option>
                                        <option value="red" {{ $hse->color_theme === 'red' ? 'selected' : '' }}>Merah Bahaya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                    <input type="number" name="order" value="{{ $hse->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>

                                <div class="flex items-center pt-6">
                                    <label class="relative flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $hse->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                        <span class="text-xs font-bold text-slate-700">Aktifkan Item</span>
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
            <div class="col-span-4 p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                Belum ada standar HSE yang ditambahkan.
            </div>
            @endforelse
        </div>

        <!-- Add Modal -->
        <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/80 backdrop-blur-sm text-slate-800" x-cloak>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-base text-navy-900">Tambah Standar HSE Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.content.hse.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Protokol HSE *</label>
                        <input type="text" name="title" required placeholder="Contoh: Kalibrasi Katup Pengaman (Relief Valve)" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Protokol *</label>
                        <textarea name="description" rows="3" required placeholder="Jelaskan standar pemeriksaan dan kepatuhan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                            <input type="text" name="icon" value="fa-solid fa-shield-halved" placeholder="fa-solid fa-icon" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Aksen</label>
                            <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                <option value="brand">Biru Brand</option>
                                <option value="emerald">Hijau Emerald</option>
                                <option value="sky">Biru Langit</option>
                                <option value="red">Merah Bahaya</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ count($hseItems) + 1 }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Item</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Tambah Standar HSE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
