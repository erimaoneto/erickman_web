@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna & Hak Akses Portal')

@section('content')

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Pengguna Portal</h2>
                <p class="text-xs text-slate-500">Kelola akun administrator, staf keuangan, dan wewenang akses portal.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Pengguna</span>
            </a>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
                    <span class="text-2xl font-black text-navy-900 mt-1 block">{{ $totalUsers }} User</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-navy-900 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Administrator</span>
                    <span class="text-2xl font-black text-blue-600 mt-1 block">{{ $totalAdmin }} Akun</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Staf Keuangan</span>
                    <span class="text-2xl font-black text-amber-600 mt-1 block">{{ $totalKeuangan }} Akun</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau telepon..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                </div>

                <div class="sm:w-48">
                    <select name="role" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Semua Peran --</option>
                        <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Administrator (Superadmin)</option>
                        <option value="keuangan" {{ request('role') == 'keuangan' ? 'selected' : '' }}>Keuangan (Staf Keuangan)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-navy-900 text-white font-bold text-xs hover:bg-navy-800 transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-base text-navy-900">Daftar Akun Pengguna</h3>
                <span class="text-xs text-slate-500">{{ $users->total() }} Akun Terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Nama Pengguna</th>
                            <th class="py-3.5 px-4">Email Login</th>
                            <th class="py-3.5 px-4">Hak Akses (Role)</th>
                            <th class="py-3.5 px-4">Nomor Kontak</th>
                            <th class="py-3.5 px-4">Terdaftar Sejak</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-900 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-navy-900 block">{{ $u->name }}</span>
                                    @if($u->id === auth()->id())
                                    <span class="text-[10px] text-brand-600 font-semibold">(Akun Anda)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-700 font-medium">
                                {{ $u->email }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($u->role === 'superadmin')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-[9px]"></i> Administrator
                                </span>
                                @elseif($u->role === 'keuangan')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-coins text-[9px]"></i> Staf Keuangan
                                </span>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ ucfirst($u->role) }}
                                </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $u->phone ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-slate-400 hover:text-brand-600 transition" title="Edit Akun">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $u->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Hapus Akun">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data pengguna ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>

@endsection
