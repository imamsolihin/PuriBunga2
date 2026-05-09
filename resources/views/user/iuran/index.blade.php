@extends('layouts.user')
@section('title', 'Iuran Saya')
@section('page-title', 'Riwayat Iuran')
@section('content')
<div class="space-y-6">
    {{-- Header Info --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-slate-800 font-black text-2xl tracking-tight">Daftar Tagihan & Riwayat</h3>
            <p class="text-slate-400 text-sm font-medium mt-1">Transparansi pembayaran iuran warga Puri Bunga 2</p>
        </div>
        <div class="flex gap-3">
            <div class="px-5 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Total Terbayar</p>
                <p class="text-green-600 font-black text-xl">Rp {{ number_format($iurans->where('status_pembayaran','lunas')->sum('nominal'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter/Search Placeholder --}}
    <div class="card-stat p-6 flex flex-wrap gap-4 items-center">
        <form method="GET" class="flex flex-wrap gap-4 w-full md:w-auto">
            <select name="tahun" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Tahun</option>
                @foreach(range(date('Y'), date('Y')-2) as $year)
                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                @endforeach
            </select>
            <select name="status" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
            <button type="submit" class="bg-[#0f2557] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1a3a8f] transition-all">Filter</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="card-stat overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="text-left py-4 px-6 text-xs text-slate-400 font-bold uppercase tracking-[0.1em]">Kategori</th>
                        <th class="text-left py-4 px-6 text-xs text-slate-400 font-bold uppercase tracking-[0.1em]">Bulan/Tahun</th>
                        <th class="text-right py-4 px-6 text-xs text-slate-400 font-bold uppercase tracking-[0.1em]">Nominal</th>
                        <th class="text-center py-4 px-6 text-xs text-slate-400 font-bold uppercase tracking-[0.1em]">Status</th>
                        <th class="text-left py-4 px-6 text-xs text-slate-400 font-bold uppercase tracking-[0.1em]">Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($iurans as $i)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-5 px-6">
                            <p class="text-slate-800 font-bold">{{ $i->kategoriIuran->nama_kategori }}</p>
                        </td>
                        <td class="py-5 px-6">
                            <span class="text-slate-500 font-medium">{{ $i->bulan }}</span>
                            <span class="text-slate-300 font-black ml-1">{{ $i->tahun }}</span>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <p class="text-slate-800 font-black">Rp {{ number_format($i->nominal, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $i->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700 shadow-sm shadow-yellow-100' }}">
                                {{ $i->status_pembayaran }}
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <p class="text-slate-400 font-medium italic text-xs">
                                {{ $i->tanggal_bayar ? $i->tanggal_bayar->format('d/m/Y') : '-' }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="text-slate-400 font-medium">Belum ada data iuran yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
