@extends('layouts.admin')
@section('title', 'Detail Warga')
@section('page-title', 'Detail Warga')
@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="card-stat p-6 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center flex-shrink-0">
            <span class="text-white text-2xl font-extrabold">{{ strtoupper(substr($warga->nama_lengkap, 0, 1)) }}</span>
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-extrabold text-slate-800">{{ $warga->nama_lengkap }}</h2>
            <p class="text-slate-500 text-sm">Blok {{ $warga->blok_rumah }} / No. {{ $warga->nomor_rumah }}</p>
            <span class="mt-1 inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $warga->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">{{ ucfirst($warga->status) }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.warga.edit', $warga) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition-colors">Edit</a>
            <a href="{{ route('admin.warga.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition-colors">Kembali</a>
        </div>
    </div>
    <div class="card-stat p-6">
        <h3 class="font-bold text-slate-800 mb-4">Riwayat Iuran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Kategori</th>
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Bulan/Tahun</th>
                        <th class="text-right py-2 px-3 text-slate-500 font-semibold text-xs">Nominal</th>
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($warga->iurans as $iuran)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-3">{{ $iuran->kategoriIuran->nama_kategori }}</td>
                        <td class="py-2 px-3 text-slate-600">{{ $iuran->bulan }} {{ $iuran->tahun }}</td>
                        <td class="py-2 px-3 text-right font-semibold">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $iuran->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($iuran->status_pembayaran) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat iuran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
