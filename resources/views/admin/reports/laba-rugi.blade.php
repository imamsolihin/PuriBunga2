@extends('layouts.admin')
@section('title', 'Laporan Laba Rugi')
@section('page-title', 'Laporan Kinerja Keuangan (Laba Rugi)')
@section('content')
<div class="space-y-6">
    {{-- Filter --}}
    <div class="card-stat p-6">
        <form method="GET" action="{{ route('admin.laporan.laba-rugi') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="pt-6 flex gap-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Filter</button>
                <a href="{{ route('admin.laporan.laba-rugi', array_merge(request()->all(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="card-stat p-6 overflow-hidden">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Paguyuban Puri Bunga 2</h2>
            <h3 class="text-lg font-semibold text-slate-700">Laporan Kinerja Keuangan</h3>
            <p class="text-sm text-slate-500">Periode {{ date('d M Y', strtotime($startDate)) }} s.d. {{ date('d M Y', strtotime($endDate)) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Kode</th>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Akun</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {{-- Penerimaan --}}
                    <tr class="bg-slate-100 font-bold">
                        <td colspan="3" class="py-2 px-4 text-slate-700">Penerimaan</td>
                    </tr>
                    @php $totalPendapatan = 0; @endphp
                    @foreach($pendapatan as $coa)
                        @php
                            $balance = $coa->total_kredit - $coa->total_debit; // Normal balance is Credit
                            $totalPendapatan += $balance;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-4 text-slate-600">{{ $coa->kode_akun }}</td>
                            <td class="py-2 px-4 text-slate-800 font-medium">{{ $coa->nama_akun }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-slate-50">
                        <td colspan="2" class="py-2 px-4 text-slate-700">Total Penerimaan</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Pengeluaran --}}
                    <tr class="bg-slate-100 font-bold mt-4">
                        <td colspan="3" class="py-2 px-4 text-slate-700">Pengeluaran</td>
                    </tr>
                    @php $totalBeban = 0; @endphp
                    @foreach($beban as $coa)
                        @php
                            $balance = $coa->total_debit - $coa->total_kredit; // Normal balance is Debit
                            $totalBeban += $balance;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-4 text-slate-600">{{ $coa->kode_akun }}</td>
                            <td class="py-2 px-4 text-slate-800 font-medium">{{ $coa->nama_akun }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-slate-50">
                        <td colspan="2" class="py-2 px-4 text-slate-700">Total Pengeluaran</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalBeban, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Surplus/Defisit --}}
                    @php $surplus = $totalPendapatan - $totalBeban; @endphp
                    <tr class="font-extrabold text-lg {{ $surplus < 0 ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                        <td colspan="2" class="py-3 px-4">Surplus / Defisit</td>
                        <td class="py-3 px-4 text-right">{{ number_format($surplus, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
