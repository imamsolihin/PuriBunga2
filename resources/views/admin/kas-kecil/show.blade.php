@extends('layouts.admin')
@section('title', 'Detail Kas Kecil')
@section('page-title', 'Detail Transaksi Kas Kecil')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="card-stat p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Keterangan Transaksi</p>
                <h2 class="text-xl font-extrabold text-slate-800 mt-1">{{ $kasKecil->keterangan }}</h2>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $kasKecil->jenis === 'masuk' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ strtoupper($kasKecil->jenis) }}
                </span>
                <p class="text-slate-500 text-xs mt-2 font-medium">{{ $kasKecil->tanggal->format('d F Y') }}</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-8 mb-8">
            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-xs text-slate-500 font-semibold uppercase">Nominal</p>
                <p class="text-2xl font-black text-slate-800 mt-1">Rp {{ number_format($kasKecil->nominal, 0, ',', '.') }}</p>
            </div>
            @if($kasKecil->jurnal)
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs text-blue-600 font-semibold uppercase">ID Jurnal Terkait</p>
                    <p class="text-lg font-bold text-blue-800 mt-1">#{{ $kasKecil->jurnal->id }}</p>
                </div>
                <a href="{{ route('admin.jurnal.show', $kasKecil->jurnal) }}" class="text-blue-600 hover:bg-blue-100 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
            @endif
        </div>

        @if($kasKecil->jurnal)
        <div class="space-y-3">
            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider px-1">Review Entri Jurnal Otomatis</p>
            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left py-2 px-4 text-xs text-slate-500">Akun</th>
                            <th class="text-right py-2 px-4 text-xs text-slate-500">Debit</th>
                            <th class="text-right py-2 px-4 text-xs text-slate-500">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($kasKecil->jurnal->details as $d)
                        <tr>
                            <td class="py-2 px-4">
                                <div class="font-mono text-xs text-blue-700 font-bold">{{ $d->coa->kode_akun }}</div>
                                <div class="text-slate-700 font-medium">{{ $d->coa->nama_akun }}</div>
                            </td>
                            <td class="py-2 px-4 text-right font-semibold {{ $d->debit > 0 ? 'text-green-600' : 'text-slate-300' }}">
                                {{ $d->debit > 0 ? 'Rp '.number_format($d->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-2 px-4 text-right font-semibold {{ $d->kredit > 0 ? 'text-red-600' : 'text-slate-300' }}">
                                {{ $d->kredit > 0 ? 'Rp '.number_format($d->kredit, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.kas-kecil.edit', $kasKecil) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Edit Transaksi</a>
        <a href="{{ route('admin.kas-kecil.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">Kembali</a>
    </div>
</div>
@endsection
