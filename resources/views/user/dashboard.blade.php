@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-title', 'Halo, ' . auth()->user()->name . '!')
@section('content')
<div class="space-y-8">
    {{-- Info Card --}}
    <div class="card-stat p-8 bg-gradient-to-br from-[#0f2557] to-[#1a3a8f] relative overflow-hidden group">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="text-blue-200 text-xs font-bold uppercase tracking-[0.2em] mb-2">Informasi Hunian</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-white text-3xl font-black">Blok {{ $warga->blok_rumah }}</p>
                    <p class="text-blue-300 text-xl font-bold">/ No. {{ $warga->nomor_rumah }}</p>
                </div>
                <p class="text-blue-100/60 text-sm mt-3 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Terdaftar sebagai warga aktif Puri Bunga 2
                </p>
            </div>
            <div class="flex gap-4">
                <div class="text-center bg-white/10 backdrop-blur-md rounded-2xl p-4 min-w-[100px] border border-white/10">
                    <p class="text-white text-xl font-black">{{ $totalLunas }}</p>
                    <p class="text-blue-200 text-[10px] font-bold uppercase">Lunas</p>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-md rounded-2xl p-4 min-w-[100px] border border-white/10">
                    <p class="text-white text-xl font-black">{{ $totalBelum }}</p>
                    <p class="text-blue-200 text-[10px] font-bold uppercase">Belum</p>
                </div>
            </div>
        </div>
        {{-- Decor --}}
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all duration-700"></div>
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
                                <p class="text-slate-800 font-bold text-sm">{{ $i->kategoriIuran->nama_kategori }}</p>
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
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">{{ $p->created_at->format('d M Y') }}</span>
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
