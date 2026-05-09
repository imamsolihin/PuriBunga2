@extends('layouts.admin')
@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman Baru')
@section('content')
<div class="max-w-2xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Pengumuman <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Kerja Bakti Hari Minggu" required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Pengumuman <span class="text-red-500">*</span></label>
                <textarea name="isi" rows="6" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed" placeholder="Tuliskan isi pengumuman secara detail di sini..." required>{{ old('isi') }}</textarea>
                @error('isi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil di Warga)</option>
                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Publikasikan</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
