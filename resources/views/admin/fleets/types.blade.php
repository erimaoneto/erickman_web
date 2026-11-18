@extends('layouts.admin')

@section('title', 'Kelola Tipe Armada')
@section('page_title', 'Dashboard Armada: Kategori & Tipe Kendaraan')

@section('content')

    <div class="space-y-6" x-data="{ editModal: false, editId: '', editName: '', editDesc: '' }">
        <!-- Header & Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.fleets.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-navy-900 text-xs shadow-sm transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-navy-900">Kategori & Tipe Armada</h2>
                    <p class="text-xs text-slate-500">Kelola klasifikasi unit truk pengangkut yang digunakan pada sistem.</p>
                </div>
            </div>

            <a href="{{ route('admin.fleets.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Unit Truk</span>
            </a>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-sm">
            @foreach($errors->all() as $error)
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                <span>{{ $error }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Tambah Tipe Baru -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4 self-start">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-brand-600"></i>
                        <span>Tambah Tipe Baru</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tipe baru akan otomatis muncul pada pilihan formulir armada.</p>
                </div>

                <form action="{{ route('admin.fleets.types.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Tipe Armada *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Dump Truck Tronton / Wingbox" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Keterangan / Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsi singkat kegunaan atau spesifikasi..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        <span>Simpan Tipe Armada</span>
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Tipe Armada -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-navy-900">Daftar Tipe Armada Terdaftar</h3>
                        <p class="text-xs text-slate-500">Total {{ $types->count() }} kategori armada aktif.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Tipe Armada</th>
                                <th class="py-3.5 px-4">Deskripsi</th>
                                <th class="py-3.5 px-4 text-center">Unit Terdaftar</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($types as $idx => $t)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-navy-900 text-sm">{{ $t->name }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $t->description ?: '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $t->fleets_count > 0 ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $t->fleets_count }} Truk
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" @click="editModal = true; editId = '{{ $t->id }}'; editName = '{{ addslashes($t->name) }}'; editDesc = '{{ addslashes($t->description ?? '') }}'" class="p-2 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-600 transition" title="Edit Tipe">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>

                                        @if($t->fleets_count === 0)
                                        <form action="{{ route('admin.fleets.types.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tipe armada {{ $t->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition" title="Hapus Tipe">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Tidak dapat dihapus karena sedang digunakan unit armada">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada tipe armada yang didaftarkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Edit Tipe Armada -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 space-y-4" @click.away="editModal = false">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-base text-navy-900">Perbarui Tipe Armada</h3>
                        <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('/admin/fleets/types') }}/' + editId" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Tipe Armada *</label>
                            <input type="text" name="name" x-model="editName" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <p class="text-[10px] text-slate-400 mt-1">Mengubah nama tipe akan memperbarui semua unit armada terkait secara otomatis.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Keterangan / Deskripsi</label>
                            <textarea name="description" x-model="editDesc" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                            <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
