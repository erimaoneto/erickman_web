@extends('layouts.admin')

@section('title', isset($user) ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru')
@section('page_title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Portal')

@section('content')

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">{{ isset($user) ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru' }}</h2>
                    <p class="text-xs text-slate-500">
                        {{ isset($user) ? 'Perbarui informasi akun, hak akses atau atur ulang kata sandi.' : 'Tambahkan akun admin atau staf keuangan baru untuk mengelola portal.' }}
                    </p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-800 space-y-1">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required placeholder="Contoh: Budi Pratama" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alamat Email (Untuk Login) *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required placeholder="contoh@erickman.co.id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Kata Sandi (Password) {{ isset($user) ? '(Kosongkan jika tidak diubah)' : '*' }}
                    </label>
                    <input type="password" name="password" {{ isset($user) ? '' : 'required' }} placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Hak Akses / Peran (Role) *</label>
                    <select name="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                        <option value="keuangan" {{ old('role', $user->role ?? '') === 'keuangan' ? 'selected' : '' }}>
                            Keuangan — Akses modul Buku Kas & Transaksi Keuangan
                        </option>
                        <option value="superadmin" {{ old('role', $user->role ?? '') === 'superadmin' ? 'selected' : '' }}>
                            Superadmin / Administrator — Akses penuh ke seluruh fitur & pengguna
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+62 812 XXXX XXXX" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">
                        {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
