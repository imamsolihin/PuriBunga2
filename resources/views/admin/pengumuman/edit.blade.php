@extends('layouts.admin')
@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('content')
<div class="max-w-2xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.pengumuman.update', $pengumuman) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Pengumuman</label>
                <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Pengumuman</label>
                <textarea name="isi" rows="6" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed" required>{{ old('isi', $pengumuman->isi) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="aktif" {{ old('status', $pengumuman->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $pengumuman->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Simpan Perubahan</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
