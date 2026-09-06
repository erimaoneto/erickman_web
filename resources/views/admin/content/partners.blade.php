@extends('layouts.admin')

@section('title', 'Kelola Rekanan & Kemitraan Strategis')
@section('page_title', 'CMS: Rekanan Kami & Mitra Strategis')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Rekanan & Klien Strategis</h2>
                <p class="text-xs text-slate-500">Logo dan nama perusahaan mitra yang tampil pada seksi Rekanan Kami.</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Mitra Rekanan</span>
            </button>
        </div>

        <!-- Partners Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
            @forelse($partners as $partner)
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between text-center relative group" x-data="{ editModal: false }">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">#{{ $partner->order }}</span>
                        <span class="w-2 h-2 rounded-full {{ $partner->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}" title="{{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}"></span>
                    </div>

                    <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center text-2xl font-black mb-3 {{ $partner->color_theme === 'orange' ? 'bg-orange-50 text-orange-600' : ($partner->color_theme === 'red' ? 'bg-red-50 text-red-600' : ($partner->color_theme === 'sky' ? 'bg-sky-50 text-sky-600' : ($partner->color_theme === 'emerald' ? 'bg-emerald-50 text-emerald-600' : ($partner->color_theme === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600')))) }}">
                        @if($partner->logo_path)
                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="w-10 h-10 object-contain">
                        @else
                        <i class="{{ $partner->icon ?: 'fa-solid fa-building' }}"></i>
                        @endif
                    </div>

                    <h4 class="font-extrabold text-sm text-navy-900 leading-snug">{{ $partner->name }}</h4>
                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $partner->subtitle }}</p>
                </div>

                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-center gap-2 text-xs">
                    <button @click="editModal = true" class="p-1.5 rounded-lg text-slate-600 hover:text-brand-600 hover:bg-slate-100 transition" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <form action="{{ route('admin.content.partners.delete', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus rekanan {{ $partner->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal -->
                <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm text-left" x-cloak>
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="editModal = false">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <h3 class="font-bold text-base text-navy-900">Edit Rekanan</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.content.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Rekanan / Singkatan *</label>
                                <input type="text" name="name" value="{{ $partner->name }}" required placeholder="Contoh: Pertamina Gas" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Legal / Keterangan</label>
                                <input type="text" name="subtitle" value="{{ $partner->subtitle }}" placeholder="Contoh: PT Pertamina Gas Tbk" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                                    <input type="text" name="icon" value="{{ $partner->icon }}" placeholder="fa-solid fa-building" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Background</label>
                                    <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                        <option value="orange" {{ $partner->color_theme === 'orange' ? 'selected' : '' }}>Oranye</option>
                                        <option value="red" {{ $partner->color_theme === 'red' ? 'selected' : '' }}>Merah</option>
                                        <option value="sky" {{ $partner->color_theme === 'sky' ? 'selected' : '' }}>Biru Langit</option>
                                        <option value="blue" {{ $partner->color_theme === 'blue' ? 'selected' : '' }}>Biru Tua</option>
                                        <option value="emerald" {{ $partner->color_theme === 'emerald' ? 'selected' : '' }}>Hijau</option>
                                        <option value="amber" {{ $partner->color_theme === 'amber' ? 'selected' : '' }}>Amber / Emas</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Upload Logo Khusus (Opsional)</label>
                                @if($partner->logo_path)
                                <div class="mb-2 flex items-center gap-2">
                                    <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="h-8 w-auto object-contain border p-1 rounded">
                                    <span class="text-xs text-slate-500">Logo aktif</span>
                                </div>
                                @endif
                                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                    <input type="number" name="order" value="{{ $partner->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>

                                <div class="flex items-center pt-6">
                                    <label class="relative flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $partner->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                        <span class="text-xs font-bold text-slate-700">Aktifkan Rekanan</span>
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
            <div class="col-span-6 p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                Belum ada rekanan terdaftar.
            </div>
            @endforelse
        </div>

        <!-- Add Modal -->
        <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 text-left" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-base text-navy-900">Tambah Rekanan Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.content.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Rekanan / Singkatan *</label>
                        <input type="text" name="name" required placeholder="Contoh: Pertamina Gas" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Legal / Keterangan</label>
                        <input type="text" name="subtitle" placeholder="Contoh: PT Pertamina Gas Tbk" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Icon FontAwesome</label>
                            <input type="text" name="icon" value="fa-solid fa-building" placeholder="fa-solid fa-building" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Warna Background</label>
                            <select name="color_theme" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                <option value="blue">Biru Tua</option>
                                <option value="orange">Oranye</option>
                                <option value="red">Merah</option>
                                <option value="sky">Biru Langit</option>
                                <option value="emerald">Hijau</option>
                                <option value="amber">Amber / Emas</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Upload Logo Khusus (Opsional)</label>
                        <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ count($partners) + 1 }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Rekanan</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Tambah Rekanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
