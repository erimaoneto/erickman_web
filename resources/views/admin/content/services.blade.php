@extends('layouts.admin')

@section('title', 'Kelola Layanan Perusahaan')
@section('page_title', 'CMS: Layanan & KBLI')

@section('content')

    <div class="space-y-6">
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Daftar Layanan Industri (KBLI Resmi)</h2>
                <p class="text-xs text-slate-500">Layanan yang ditawarkan kepada klien korporat dan industri.</p>
            </div>
            <a href="{{ route('admin.content.services.create') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Layanan Baru</span>
            </a>
        </div>

        <!-- Table of Services -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Urutan</th>
                            <th class="py-3.5 px-4">Gambar</th>
                            <th class="py-3.5 px-4">Nama Layanan</th>
                            <th class="py-3.5 px-4">Kode KBLI</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($services as $service)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">#{{ $service->order }}</td>
                            <td class="py-3.5 px-4">
                                <img src="{{ asset($service->image_path ?? '/images/truck-cng-green.jpg') }}" class="w-12 h-9 object-cover rounded-lg border border-slate-200">
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-navy-900 text-sm block">{{ $service->title }}</span>
                                <span class="text-[11px] text-slate-400 line-clamp-1">{{ $service->short_description }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                {{ $service->kbli_code ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $service->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.content.services.edit', $service->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-600 transition inline-block">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.content.services.delete', $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus layanan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
