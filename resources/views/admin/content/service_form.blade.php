@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan Baru')
@section('page_title', isset($service) ? 'Edit Layanan: ' . $service->title : 'Tambah Layanan Baru')

@section('content')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">{{ isset($service) ? 'Perbarui Rincian Layanan' : 'Formulir Layanan Baru' }}</h2>
                    <p class="text-xs text-slate-500">Layanan akan langsung ditampilkan pada halaman website utama.</p>
                </div>
                <a href="{{ route('admin.content.services') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ isset($service) ? route('admin.content.services.update', $service->id) : route('admin.content.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if(isset($service))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Layanan *</label>
                    <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}" required placeholder="Contoh: Distribusi Gas Alam Terkompresi (CNG)" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kode KBLI (Sesuai NIB)</label>
                        <input type="text" name="kbli_code" value="{{ old('kbli_code', $service->kbli_code ?? '') }}" placeholder="Contoh: KBLI 35201 & 35202" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Urutan Tampilan</label>
                        <input type="number" name="order" value="{{ old('order', $service->order ?? 0) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Deskripsi Singkat (Tampil pada Card Beranda) *</label>
                    <textarea name="short_description" rows="2" required placeholder="Penjelasan ringkas 1-2 kalimat..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Detail Lengkap Layanan (Halaman Khusus)</label>
                    <textarea name="description" rows="5" placeholder="Penjelasan mendalam mengenai armada, SOP, dan cakupan layanan..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">{{ old('description', $service->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Foto Ilustrasi Layanan</label>
                    @if(isset($service) && $service->image_path)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset($service->image_path) }}" class="w-20 h-14 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Gambar saat ini. Pilih file baru jika ingin mengganti.</span>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-600">
                        <span>Aktifkan dan Tampilkan pada Website</span>
                    </label>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.content.services') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">
                        {{ isset($service) ? 'Perbarui Layanan' : 'Simpan Layanan Baru' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
