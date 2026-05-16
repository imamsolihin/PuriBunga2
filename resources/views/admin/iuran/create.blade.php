@extends('layouts.admin')
@section('title', 'Tambah Iuran')
@section('page-title', 'Tambah Data Iuran')
@section('content')
<div class="max-w-2xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.iuran.store') }}" class="space-y-4" x-data="{ status: '{{ old('status_pembayaran','belum') }}' }">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Warga <span class="text-red-500">*</span></label>
                <select name="warga_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Warga...</option>
                    @foreach($wargas as $w)
                    <option value="{{ $w->id }}" {{ old('warga_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }} - Blok {{ $w->blok_rumah }}/{{ $w->nomor_rumah }}</option>
                    @endforeach
                </select>
                @error('warga_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori Iuran <span class="text-red-500">*</span></label>
                <select name="kategori_iuran_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Kategori...</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_iuran_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }} (Default: Rp {{ number_format($kat->nominal_default, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                @error('kategori_iuran_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan <span class="text-red-500">*</span></label>
                    <select name="bulan" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}" {{ old('bulan') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="nominal" value="{{ old('nominal', 100000) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" required>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Pembayaran</label>
                    <select name="status_pembayaran" x-model="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                <div x-show="status === 'lunas'" x-cloak>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Simpan Iuran</button>
                <a href="{{ route('admin.iuran.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
