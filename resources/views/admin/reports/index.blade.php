@extends('layouts.admin')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Pusat Laporan Keuangan')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-slate-500">Pilih laporan yang ingin Anda lihat atau kelola.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.laporan.create-warga-users') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors shadow flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin membuat akun user untuk semua warga yang belum memiliki akun?')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Buat Akun Warga
        </a>
        <a href="{{ route('admin.laporan.import-csv') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors shadow flex items-center gap-2" onclick="return confirm('⚠️ PERHATIAN: Tindakan ini akan menghapus SEMUA data jurnal dan COA yang ada saat ini dan menggantinya dengan data dari file CSV (2025 & 2026). Apakah Anda yakin?')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Reset & Impor Data CSV
        </a>
    </div>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Buku Besar --}}
    <a href="{{ route('admin.laporan.buku-besar') }}" class="card-stat p-6 hover:ring-2 hover:ring-blue-500 transition-all group">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Buku Besar</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Lihat detail mutasi transaksi untuk setiap akun perkiraan (COA) dalam periode tertentu.</p>
    </a>

    {{-- Neraca Saldo --}}
    <a href="{{ route('admin.laporan.neraca-saldo') }}" class="card-stat p-6 hover:ring-2 hover:ring-indigo-500 transition-all group">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Neraca Saldo</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Ringkasan saldo akhir dari seluruh akun perkiraan untuk memastikan keseimbangan debit dan kredit.</p>
    </a>

    {{-- Laporan Kas --}}
    <a href="{{ route('admin.laporan.kas') }}" class="card-stat p-6 hover:ring-2 hover:ring-green-500 transition-all group">
        <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Laporan Kas</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Pantau arus kas masuk dan keluar yang dikelompokkan per akun operasional dan non-operasional.</p>
    </a>

    {{-- Neraca YTD --}}
    <a href="{{ route('admin.laporan.neraca-ytd') }}" class="card-stat p-6 hover:ring-2 hover:ring-blue-500 transition-all group">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Neraca YTD</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Laporan posisi keuangan (Aset, Kewajiban, Modal) akumulatif sampai tanggal tertentu.</p>
    </a>

    {{-- Laba Rugi --}}
    <a href="{{ route('admin.laporan.laba-rugi') }}" class="card-stat p-6 hover:ring-2 hover:ring-yellow-500 transition-all group">
        <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 mb-4 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Laporan Laba Rugi</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Pantau kinerja keuangan (Pendapatan vs Beban) untuk melihat surplus atau defisit.</p>
    </a>

    {{-- Laporan Iuran (Placeholder) --}}
    <div class="card-stat p-6 opacity-60 grayscale cursor-not-allowed">
        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 mb-1">Laporan Iuran Warga</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Pantau rekapitulasi pembayaran iuran per warga, per blok, atau per kategori iuran.</p>
    </div>
</div>
@endsection
