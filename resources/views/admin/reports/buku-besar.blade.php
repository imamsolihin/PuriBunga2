@extends('layouts.admin')
@section('title', 'Laporan Buku Besar')
@section('page-title', 'Buku Besar')
@section('content')
<div class="space-y-6">
    <div class="card-stat p-6">
        <form method="GET" action="{{ route('admin.laporan.buku-besar') }}" class="grid sm:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-1">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Pilih Akun</label>
                <select name="coa_id" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="">-- Pilih COA --</option>
                    @foreach($coas as $coa)
                        <option value="{{ $coa->id }}" {{ request('coa_id') == $coa->id ? 'selected' : '' }}>
                            {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-[#0f2557] text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">Filter</button>
                <button type="button" onclick="window.print()" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">Cetak</button>
                <a href="{{ route('admin.laporan.buku-besar', array_merge(request()->all(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-xl text-sm font-semibold transition-colors shadow inline-flex items-center justify-center">
                    Excel
                </a>
            </div>
        </form>
    </div>

    @if($selectedCoa)
    <div class="card-stat overflow-hidden animate-in">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-slate-800">{{ $selectedCoa->nama_akun }}</h3>
                <p class="text-xs text-slate-500">Kode: {{ $selectedCoa->kode_akun }} | Tipe: {{ ucfirst($selectedCoa->tipe) }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-semibold uppercase">Saldo Awal</p>
                <p class="font-bold text-slate-800">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs text-slate-400 font-bold uppercase">Tanggal</th>
                    <th class="py-3 px-6 text-left text-xs text-slate-400 font-bold uppercase">Keterangan</th>
                    <th class="py-3 px-6 text-right text-xs text-slate-400 font-bold uppercase">Debit</th>
                    <th class="py-3 px-6 text-right text-xs text-slate-400 font-bold uppercase">Kredit</th>
                    <th class="py-3 px-6 text-right text-xs text-slate-400 font-bold uppercase">Saldo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php $currentSaldo = $saldoAwal; @endphp
                @forelse($details as $detail)
                    @php
                        if (in_array($selectedCoa->tipe, ['aset', 'beban'])) {
                            $currentSaldo += ($detail->debit - $detail->kredit);
                        } else {
                            $currentSaldo += ($detail->kredit - $detail->debit);
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-slate-600">{{ $detail->jurnal->tanggal->format('d/m/Y') }}</td>
                        <td class="py-4 px-6 font-medium text-slate-700">{{ $detail->jurnal->keterangan }}</td>
                        <td class="py-4 px-6 text-right {{ $detail->debit > 0 ? 'text-green-600 font-semibold' : 'text-slate-300' }}">
                            {{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right {{ $detail->kredit > 0 ? 'text-red-600 font-semibold' : 'text-slate-300' }}">
                            {{ $detail->kredit > 0 ? 'Rp ' . number_format($detail->kredit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right font-bold text-slate-800">
                            Rp {{ number_format($currentSaldo, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center text-slate-400">Tidak ada transaksi dalam periode ini</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-slate-50 border-t border-slate-200">
                <tr>
                    <td colspan="4" class="py-4 px-6 text-right text-xs font-bold text-slate-500 uppercase">Saldo Akhir</td>
                    <td class="py-4 px-6 text-right font-extrabold text-[#0f2557] text-base">
                        Rp {{ number_format($currentSaldo, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="card-stat p-20 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-slate-500 font-medium">Pilih akun COA dan periode tanggal untuk melihat laporan buku besar</h3>
    </div>
    @endif
</div>
@endsection
