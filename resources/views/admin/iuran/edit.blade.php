@extends('layouts.admin')
@section('title', 'Edit Iuran')
@section('page-title', 'Edit Data Iuran')
@section('content')
<div class="max-w-2xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.iuran.update', $iuran) }}" class="space-y-4" x-data="{ status: '{{ old('status_pembayaran', $iuran->status_pembayaran) }}' }">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Warga</label>
                <select name="warga_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($wargas as $w)
                    <option value="{{ $w->id }}" {{ $iuran->warga_id == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }} - Blok {{ $w->blok_rumah }}/{{ $w->nomor_rumah }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori Iuran</label>
                <select name="kategori_iuran_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ $iuran->kategori_iuran_id == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan</label>
                    <select name="bulan" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}" {{ $iuran->bulan === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun</label>
                    <input type="number" name="tahun" value="{{ $iuran->tahun }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal (Rp)</label>
                <input type="number" name="nominal" value="{{ $iuran->nominal }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <select name="status_pembayaran" x-model="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                <div x-show="status === 'lunas'" x-cloak>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', optional($iuran->tanggal_bayar)->format('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Update Iuran</button>
                <a href="{{ route('admin.iuran.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
