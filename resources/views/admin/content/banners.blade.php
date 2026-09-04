@extends('layouts.admin')

@section('title', 'Kelola Banner Slider Beranda')
@section('page_title', 'CMS: Banner & Slider Foto Armada')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Banner Slider Utama</h2>
                <p class="text-xs text-slate-500">Banner foto hero yang tampil di bagian atas beranda website.</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Banner Slider</span>
            </button>
        </div>

        <!-- Banner Cards List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($banners as $banner)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between" x-data="{ editModal: false }">
                <div>
                    <!-- Image Preview -->
                    <div class="h-52 relative overflow-hidden bg-slate-900">
                        <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 bg-navy-900/90 text-white font-bold text-xs px-2.5 py-1 rounded">
                            Urutan #{{ $banner->order }}
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $banner->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white' }}">
                                {{ $banner->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-6 space-y-3">
                        @if($banner->tagline)
                        <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider block">{{ $banner->tagline }}</span>
                        @endif
                        <h3 class="font-bold text-base text-navy-900 leading-snug">{{ $banner->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $banner->description }}</p>

                        @if($banner->button_text)
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-lg text-xs font-semibold text-slate-700 border border-slate-200">
                                <i class="fa-solid fa-arrow-pointer text-brand-600"></i>
                                <span>{{ $banner->button_text }} &rarr; {{ $banner->button_url }}</span>
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <button @click="editModal = true" class="text-xs font-bold text-slate-700 hover:text-brand-600 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Banner</span>
                    </button>

                    <form action="{{ route('admin.content.banners.delete', $banner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal -->
                <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
                    <div @click.away="editModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-2xl">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-bold text-base text-navy-900">Edit Banner Slider</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                        </div>

                        <form action="{{ route('admin.content.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Headline *</label>
                                <input type="text" name="title" value="{{ $banner->title }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tagline / Subjudul</label>
                                <input type="text" name="tagline" value="{{ $banner->tagline }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Ringkas</label>
                                <textarea name="description" rows="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">{{ $banner->description }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Foto Banner (Opsional)</label>
                                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Teks Tombol</label>
                                    <input type="text" name="button_text" value="{{ $banner->button_text }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Tombol</label>
                                    <input type="text" name="button_url" value="{{ $banner->button_url }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 items-center pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan Tampil</label>
                                    <input type="number" name="order" value="{{ $banner->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                </div>
                                <div class="pt-5">
                                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                        <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-600">
                                        <span>Aktifkan Banner</span>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end gap-2">
                                <button @click="editModal = false" type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Batal</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add Banner Modal -->
        <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
            <div @click.away="addModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-base text-navy-900">Tambah Banner Slider Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form action="{{ route('admin.content.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Headline *</label>
                        <input type="text" name="title" required placeholder="Contoh: Distribusi Gas Alam Terkompresi (CNG)" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tagline / Subjudul</label>
                        <input type="text" name="tagline" placeholder="Contoh: Energi Andal untuk Industri" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" placeholder="Deskripsi ringkas yang menarik perhatian pengunjung..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload Foto Banner *</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Teks Tombol CTA</label>
                            <input type="text" name="button_text" placeholder="Minta Penawaran" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link URL Tombol</label>
                            <input type="text" name="button_url" value="#kontak" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                            <input type="number" name="order" value="1" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="pt-5">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600 focus:ring-brand-600">
                                <span>Aktifkan Sekarang</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button @click="addModal = false" type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow">Simpan Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
