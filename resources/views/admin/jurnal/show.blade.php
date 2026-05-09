@extends('layouts.admin')
@section('title', 'Detail Jurnal')
@section('page-title', 'Detail Jurnal')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="card-stat p-6">
        <div class="grid sm:grid-cols-3 gap-4 mb-6">
            <div><p class="text-xs text-slate-500 font-semibold">Tanggal</p><p class="text-slate-800 font-bold mt-1">{{ $jurnal->tanggal->format('d M Y') }}</p></div>
            <div><p class="text-xs text-slate-500 font-semibold">Tipe</p>
                <span class="mt-1 inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $jurnal->tipe_transaksi === 'pemasukan' ? 'bg-green-100 text-green-700' : ($jurnal->tipe_transaksi === 'pengeluaran' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600') }}">{{ ucfirst($jurnal->tipe_transaksi) }}</span>
            </div>
            <div><p class="text-xs text-slate-500 font-semibold">Total</p><p class="text-slate-800 font-extrabold text-lg mt-1">Rp {{ number_format($jurnal->total, 0, ',', '.') }}</p></div>
        </div>
        <p class="text-sm font-semibold text-slate-700 mb-4">{{ $jurnal->keterangan }}</p>
        <table class="w-full text-sm border border-slate-200 rounded-xl overflow-hidden">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left py-2 px-4 text-xs text-slate-500 font-semibold">Kode</th>
                    <th class="text-left py-2 px-4 text-xs text-slate-500 font-semibold">Akun</th>
                    <th class="text-right py-2 px-4 text-xs text-slate-500 font-semibold">Debit</th>
                    <th class="text-right py-2 px-4 text-xs text-slate-500 font-semibold">Kredit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($jurnal->details as $d)
                <tr>
                    <td class="py-2 px-4 font-mono text-blue-700 font-bold">{{ $d->coa->kode_akun }}</td>
                    <td class="py-2 px-4 text-slate-700">{{ $d->coa->nama_akun }}</td>
                    <td class="py-2 px-4 text-right {{ $d->debit > 0 ? 'text-green-600 font-semibold' : 'text-slate-400' }}">{{ $d->debit > 0 ? 'Rp ' . number_format($d->debit, 0, ',', '.') : '-' }}</td>
                    <td class="py-2 px-4 text-right {{ $d->kredit > 0 ? 'text-red-600 font-semibold' : 'text-slate-400' }}">{{ $d->kredit > 0 ? 'Rp ' . number_format($d->kredit, 0, ',', '.') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-50 border-t border-slate-200">
                <tr>
                    <td colspan="2" class="py-2 px-4 text-xs font-bold text-slate-600">Total</td>
                    <td class="py-2 px-4 text-right text-xs font-bold text-green-600">Rp {{ number_format($jurnal->details->sum('debit'), 0, ',', '.') }}</td>
                    <td class="py-2 px-4 text-right text-xs font-bold text-red-600">Rp {{ number_format($jurnal->details->sum('kredit'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.jurnal.edit', $jurnal) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">Edit Jurnal</a>
        <a href="{{ route('admin.jurnal.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">Kembali</a>
    </div>
</div>
@endsection
