@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-title', 'Halo, ' . auth()->user()->name . '!')
@section('content')
<div class="space-y-8">
    {{-- Info Card --}}
    <div class="rounded-3xl p-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <p class="text-white/70 text-xs font-bold uppercase tracking-[0.25em] mb-3">Informasi Hunian</p>
                <div class="flex items-baseline gap-3">
                    <p class="text-white text-4xl font-black drop-shadow">Blok {{ $warga->blok_rumah }}</p>
                    <p class="text-white text-2xl font-bold drop-shadow">/ No. {{ $warga->nomor_rumah }}</p>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <div class="w-5 h-5 rounded-full bg-green-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-white font-semibold text-sm">Terdaftar sebagai warga aktif Puri Bunga 2</p>
                </div>
            </div>
            <div class="flex gap-4 flex-shrink-0">
                <div class="text-center bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4 min-w-[100px] border border-white/30">
                    <p class="text-white text-3xl font-black drop-shadow">{{ $totalLunas }}</p>
                    <p class="text-white font-bold text-xs uppercase tracking-wider mt-1">✓ Lunas</p>
                </div>
                <div class="text-center bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4 min-w-[100px] border border-white/30">
                    <p class="text-white text-3xl font-black drop-shadow">{{ $totalBelum }}</p>
                    <p class="text-white font-bold text-xs uppercase tracking-wider mt-1">⏳ Belum</p>
                </div>
            </div>
        </div>
        {{-- Decorative circles --}}
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full"></div>
        <div class="absolute -right-4 -bottom-16 w-64 h-64 bg-white/5 rounded-full"></div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Recent Payments --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h4 class="text-slate-800 font-black text-lg">Iuran Terakhir</h4>
                <a href="{{ route('user.iuran') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="card-stat overflow-hidden">
                <div class="divide-y divide-slate-50">
                    @forelse($iurans as $i)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl {{ $i->status_pembayaran === 'lunas' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-slate-800 font-bold text-sm">{{ $i->kategoriIuran?->nama_kategori ?? 'Umum' }}</p>
                                <p class="text-slate-400 text-xs font-medium">{{ $i->bulan }} {{ $i->tahun }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-800 font-black text-sm">Rp {{ number_format($i->nominal, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold uppercase tracking-wider {{ $i->status_pembayaran === 'lunas' ? 'text-green-500' : 'text-yellow-500' }} mt-1">
                                {{ $i->status_pembayaran }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 text-sm">Belum ada riwayat iuran</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Latest Announcements --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h4 class="text-slate-800 font-black text-lg">Pengumuman</h4>
                <a href="{{ route('user.pengumuman') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($pengumumans as $p)
                <div class="card-stat p-6 border-l-4 border-blue-500">
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">{{ $p->created_at ? $p->created_at->format('d M Y') : '-' }}</span>
                    <h5 class="text-slate-800 font-bold text-base mt-1 mb-2">{{ $p->judul }}</h5>
                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                        {{ $p->isi }}
                    </p>
                </div>
                @empty
                <div class="card-stat p-10 text-center text-slate-400 text-sm">Tidak ada pengumuman terbaru</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
