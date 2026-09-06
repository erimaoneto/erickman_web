@extends('layouts.admin')

@section('title', 'Kelola Menu Navigasi Website')
@section('page_title', 'CMS: Menu Navigasi Header & Navbar')

@section('content')

    <div class="space-y-8" x-data="{ addModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Menu Navigasi Header</h2>
                <p class="text-xs text-slate-500">Kelola tautan menu navigasi yang tampil pada header dan mobile navigation web depan.</p>
            </div>
            <button @click="addModal = true" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Item Menu</span>
            </button>
        </div>

        <!-- Info Card -->
        <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-start gap-3">
            <i class="fa-solid fa-circle-info text-blue-600 text-base mt-0.5"></i>
            <div>
                <strong>Panduan Tautan Anchor Section:</strong>
                <p class="mt-0.5 text-blue-800">
                    Gunakan tautan anchor berikut untuk mengarahkan ke section di beranda:
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#beranda</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#tentang</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#layanan</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#armada</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#rekanan</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#keunggulan</code>,
                    <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-[11px]">#kontak</code>.
                    Anda juga dapat memasukkan URL halaman eksternal atau rute lain.
                </p>
            </div>
        </div>

        <!-- Table List -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-4 w-16 text-center">Urutan</th>
                            <th class="p-4">Label Menu</th>
                            <th class="p-4">Tujuan Tautan (URL / Anchor)</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($menus as $menu)
                        <tr class="hover:bg-slate-50 transition" x-data="{ editModal: false }">
                            <td class="p-4 text-center font-bold text-navy-900">
                                <span class="w-7 h-7 rounded-full bg-slate-100 inline-flex items-center justify-center text-xs">
                                    {{ $menu->order }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-navy-900 text-sm">
                                {{ $menu->title }}
                            </td>
                            <td class="p-4 font-mono text-slate-500">
                                {{ $menu->url }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $menu->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $menu->is_active ? 'Aktif' : 'Disembunyikan' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button @click="editModal = true" class="p-2 rounded-lg text-slate-600 hover:text-brand-600 hover:bg-slate-100 transition" title="Edit Menu">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.content.menus.delete', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu {{ $menu->title }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Menu">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
                                    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200" @click.away="editModal = false">
                                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                                            <h3 class="font-bold text-base text-navy-900">Edit Menu Navigasi</h3>
                                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>

                                        <form action="{{ route('admin.content.menus.update', $menu->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Label Menu *</label>
                                                <input type="text" name="title" value="{{ $menu->title }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tujuan Tautan (URL / Anchor) *</label>
                                                <input type="text" name="url" value="{{ $menu->url }}" required placeholder="Contoh: #layanan atau /kontak" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                                    <input type="number" name="order" value="{{ $menu->order }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                                                </div>

                                                <div class="flex items-center pt-6">
                                                    <label class="relative flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox" name="is_active" value="1" {{ $menu->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                                        <span class="text-xs font-bold text-slate-700">Aktifkan Menu</span>
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada item menu navigasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-base text-navy-900">Tambah Menu Navigasi Baru</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.content.menus.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Label Menu *</label>
                        <input type="text" name="title" required placeholder="Contoh: Galeri Armada" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tujuan Tautan (URL / Anchor) *</label>
                        <input type="text" name="url" required placeholder="Contoh: #galeri atau https://..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ count($menus) + 1 }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Menu</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">Tambah Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
