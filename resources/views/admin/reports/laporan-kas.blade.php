@extends('layouts.admin')
@section('title', 'Laporan Kas')
@section('page-title', 'Laporan Kas')
@section('content')
<div class="space-y-6">
    {{-- Filter --}}
    <div class="card-stat p-6">
        <form method="GET" action="{{ route('admin.laporan.kas') }}" class="flex flex-wrap items-center gap-4">
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
                <a href="{{ route('admin.laporan.kas', array_merge(request()->all(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow inline-flex items-center gap-2">
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
            <h3 class="text-lg font-semibold text-slate-700">Laporan Kas Tahun {{ date('Y', strtotime($startDate)) }}</h3>
            <p class="text-sm text-slate-500">{{ date('M Y', strtotime($startDate)) }} s.d. {{ date('M Y', strtotime($endDate)) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Kode</th>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Akun</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">DR (Penerimaan)</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">CR (Pengeluaran)</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {{-- Kas Operasional --}}
                    <tr class="bg-slate-100 font-bold">
                        <td colspan="5" class="py-2 px-4 text-slate-700">Kas Operasional</td>
                    </tr>
                    @php
                        $totalDrOp = 0;
                        $totalCrOp = 0;
                    @endphp
                    @foreach($kasOperasional as $coa)
                        @php
                            $dr = $coa->total_kredit ?? 0; // Receipts (Credit of revenue)
                            $cr = $coa->total_debit ?? 0;   // Payments (Debit of expense)
                            $saldo = $dr - $cr;
                            $totalDrOp += $dr;
                            $totalCrOp += $cr;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-4 text-slate-600">{{ $coa->kode_akun }}</td>
                            <td class="py-2 px-4 text-slate-800 font-medium">{{ $coa->nama_akun }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ $dr > 0 ? number_format($dr, 0, ',', '.') : '-' }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ $cr > 0 ? number_format($cr, 0, ',', '.') : '-' }}</td>
                            <td class="py-2 px-4 text-right font-semibold {{ $saldo < 0 ? 'text-red-500' : 'text-green-600' }}">
                                {{ number_format($saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-slate-50">
                        <td colspan="2" class="py-2 px-4 text-slate-700">Jumlah Kas Operasional</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalDrOp, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalCrOp, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 text-right {{ ($totalDrOp - $totalCrOp) < 0 ? 'text-red-500' : 'text-green-600' }}">
                            {{ number_format($totalDrOp - $totalCrOp, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Kenaikan dan Penurunan Kas --}}
                    <tr class="bg-slate-100 font-bold mt-4">
                        <td colspan="5" class="py-2 px-4 text-slate-700">Kenaikan dan Penurunan Kas</td>
                    </tr>
                    @php
                        $totalDrNon = 0;
                        $totalCrNon = 0;
                    @endphp
                    @foreach($nonOperasional as $coa)
                        @php
                            $dr = $coa->total_kredit ?? 0;
                            $cr = $coa->total_debit ?? 0;
                            $saldo = $dr - $cr;
                            $totalDrNon += $dr;
                            $totalCrNon += $cr;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-4 text-slate-600">{{ $coa->kode_akun }}</td>
                            <td class="py-2 px-4 text-slate-800 font-medium">{{ $coa->nama_akun }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ $dr > 0 ? number_format($dr, 0, ',', '.') : '-' }}</td>
                            <td class="py-2 px-4 text-right text-slate-700">{{ $cr > 0 ? number_format($cr, 0, ',', '.') : '-' }}</td>
                            <td class="py-2 px-4 text-right font-semibold {{ $saldo < 0 ? 'text-red-500' : 'text-green-600' }}">
                                {{ number_format($saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-slate-50">
                        <td colspan="2" class="py-2 px-4 text-slate-700">Jumlah Non-Operasional</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalDrNon, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 text-right text-slate-700">{{ number_format($totalCrNon, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 text-right {{ ($totalDrNon - $totalCrNon) < 0 ? 'text-red-500' : 'text-green-600' }}">
                            {{ number_format($totalDrNon - $totalCrNon, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Summary --}}
                    @php
                        $totalKas = ($totalDrOp - $totalCrOp) + ($totalDrNon - $totalCrNon);
                        // Saldo awal and akhir need to be calculated based on full history or just static for now?
                        // Let's calculate them from DB if possible, or just show the net change.
                        // To calculate saldo awal, we need sum of all transactions before start_date.
                        // Let's keep it simple and show the net change as "Total Kenaikan/Penurunan Kas".
                    @endphp
                    <tr class="font-extrabold bg-blue-50 text-blue-800 text-lg">
                        <td colspan="4" class="py-3 px-4">TOTAL PERUBAHAN KAS</td>
                        <td class="py-3 px-4 text-right {{ $totalKas < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($totalKas, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
