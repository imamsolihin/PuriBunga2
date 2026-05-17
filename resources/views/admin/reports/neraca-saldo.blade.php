@extends('layouts.admin')
@section('title', 'Laporan Neraca Saldo')
@section('page-title', 'Neraca Saldo')
@section('content')
<div class="space-y-6">
    <div class="card-stat p-6">
        <form method="GET" action="{{ route('admin.laporan.neraca-saldo') }}" class="grid sm:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-1">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Per Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-[#0f2557] text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">Filter</button>
                <button type="button" onclick="window.print()" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">Cetak</button>
                <a href="{{ route('admin.laporan.neraca-saldo', array_merge(request()->all(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors shadow inline-flex items-center justify-center">
                    Excel
                </a>
            </div>
        </form>
    </div>

    <div class="card-stat overflow-hidden animate-in">
        <div class="bg-slate-50 px-6 py-5 border-b border-slate-200">
            <h3 class="font-extrabold text-slate-800 text-center uppercase tracking-wider">Neraca Saldo</h3>
            <p class="text-xs text-slate-500 text-center mt-0.5">Per Tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs text-slate-400 font-bold uppercase w-32">Kode Akun</th>
                    <th class="py-3 px-6 text-left text-xs text-slate-400 font-bold uppercase">Nama Akun</th>
                    <th class="py-3 px-6 text-right text-xs text-slate-400 font-bold uppercase w-48">Debit</th>
                    <th class="py-3 px-6 text-right text-xs text-slate-400 font-bold uppercase w-48">Kredit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php 
                    $totalD = 0; 
                    $totalK = 0; 
                @endphp
                @foreach($data as $row)
                    @php 
                        $totalD += $row['debit']; 
                        $totalK += $row['kredit']; 
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-3.5 px-6 font-mono text-blue-700 font-bold">{{ $row['kode'] }}</td>
                        <td class="py-3.5 px-6 font-medium text-slate-700">{{ $row['nama'] }}</td>
                        <td class="py-3.5 px-6 text-right font-semibold {{ $row['debit'] > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                            {{ $row['debit'] > 0 ? 'Rp ' . number_format($row['debit'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-semibold {{ $row['kredit'] > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                            {{ $row['kredit'] > 0 ? 'Rp ' . number_format($row['kredit'], 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                <tr class="bg-slate-100/50">
                    <td colspan="2" class="py-4 px-6 text-right text-xs font-extrabold text-slate-600 uppercase tracking-widest">Total Saldo</td>
                    <td class="py-4 px-6 text-right font-extrabold text-blue-800 text-base">
                        Rp {{ number_format($totalD, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right font-extrabold text-blue-800 text-base">
                        Rp {{ number_format($totalK, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
        
        @if(round($totalD, 2) != round($totalK, 2))
        <div class="px-6 py-3 bg-red-50 border-t border-red-100 flex items-center gap-2 text-red-600 text-xs font-bold uppercase">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            Peringatan: Neraca tidak seimbang! Periksa kembali jurnal umum Anda.
        </div>
        @else
        <div class="px-6 py-3 bg-green-50 border-t border-green-100 flex items-center gap-2 text-green-600 text-xs font-bold uppercase">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Neraca Seimbang
        </div>
        @endif
    </div>
</div>
@endsection
