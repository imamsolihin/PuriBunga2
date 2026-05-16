@extends('layouts.admin')
@section('title', 'Tambah Pengeluaran')
@section('page-title', 'Catat Pengeluaran')
@section('content')
<div class="max-w-2xl">
    <div class="card-stat p-6">
        <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
            <strong>ℹ Catatan:</strong> Setiap pencatatan pengeluaran akan <strong>otomatis membuat entri jurnal</strong> dengan Debit dan Kredit yang seimbang.
        </div>
        <form method="POST" action="{{ route('admin.pengeluaran.store') }}" class="space-y-4">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Beli lampu jalan" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal" value="{{ old('nominal') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" required>
                </div>
            </div>
            <hr class="border-slate-100">
            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Mapping Akun Jurnal</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Akun Pengeluaran (Beban) <span class="text-red-500">*</span></label>
                    <select name="coa_beban" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Pilih Akun Pengeluaran...</option>
                        @foreach($coasBeban as $coa)
                        <option value="{{ $coa->id }}" {{ old('coa_beban') == $coa->id ? 'selected' : '' }}>{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</option>
                        @endforeach
                    </select>
                    @error('coa_beban')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Akun Pembayaran (Kas/Bank) <span class="text-red-500">*</span></label>
                    <select name="coa_kas" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Pilih Akun Pembayaran...</option>
                        @foreach($coasAset as $coa)
                        <option value="{{ $coa->id }}" {{ old('coa_kas') == $coa->id ? 'selected' : '' }}>{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</option>
                        @endforeach
                    </select>
                    @error('coa_kas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Simpan & Buat Jurnal</button>
                <a href="{{ route('admin.pengeluaran.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
