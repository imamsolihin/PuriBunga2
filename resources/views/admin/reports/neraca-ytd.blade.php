@extends('layouts.admin')
@section('title', 'Neraca YTD')
@section('page-title', 'Neraca (Year to Date)')
@section('content')
<div class="space-y-6">
    {{-- Filter --}}
    <div class="card-stat p-6">
        <form method="GET" action="{{ route('admin.laporan.neraca-ytd') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Per Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="pt-6 flex gap-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Filter</button>
                <a href="{{ route('admin.laporan.neraca-ytd', array_merge(request()->all(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow inline-flex items-center gap-2">
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
            <h3 class="text-lg font-semibold text-slate-700">Laporan Posisi Keuangan (Neraca)</h3>
            <p class="text-sm text-slate-500">Per {{ date('d M Y', strtotime($date)) }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- KIRI: ASET --}}
            <div class="space-y-4">
                <h4 class="font-bold text-slate-800 border-b pb-2">ASET</h4>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-50">
                        @php $totalAset = 0; @endphp
                        @forelse($aset as $a)
                            @php $totalAset += $a['balance']; @endphp
                            <tr>
                                <td class="py-2 text-slate-600">{{ $a['kode'] }}</td>
                                <td class="py-2 text-slate-800 font-medium">{{ $a['nama'] }}</td>
                                <td class="py-2 text-right font-semibold">{{ number_format($a['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-2 text-center text-slate-400">Tidak ada data aset</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-bold border-t-2 border-slate-200">
                            <td colspan="2" class="py-2 text-slate-700">JUMLAH ASET</td>
                            <td class="py-2 text-right text-blue-600 text-lg">{{ number_format($totalAset, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- KANAN: KEWAJIBAN & MODAL --}}
            <div class="space-y-6">
                {{-- KEWAJIBAN --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-slate-800 border-b pb-2">KEWAJIBAN</h4>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-50">
                            @php $totalKewajiban = 0; @endphp
                            @forelse($kewajiban as $k)
                                @php $totalKewajiban += $k['balance']; @endphp
                                <tr>
                                    <td class="py-2 text-slate-600">{{ $k['kode'] }}</td>
                                    <td class="py-2 text-slate-800 font-medium">{{ $k['nama'] }}</td>
                                    <td class="py-2 text-right font-semibold">{{ number_format($k['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-2 text-center text-slate-400">Tidak ada data kewajiban</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-bold border-t-2 border-slate-200">
                                <td colspan="2" class="py-2 text-slate-700">JUMLAH KEWAJIBAN</td>
                                <td class="py-2 text-right text-slate-700">{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- MODAL --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-slate-800 border-b pb-2">EKUITAS / MODAL</h4>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-50">
                            @php $totalM = 0; @endphp
                            @foreach($modalAccounts as $m)
                                @php $totalM += $m['balance']; @endphp
                                <tr>
                                    <td class="py-2 text-slate-600">{{ $m['kode'] }}</td>
                                    <td class="py-2 text-slate-800 font-medium">{{ $m['nama'] }}</td>
                                    <td class="py-2 text-right font-semibold">{{ number_format($m['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            {{-- Laba/Rugi --}}
                            <tr>
                                <td class="py-2 text-slate-600">-</td>
                                <td class="py-2 text-slate-800 font-medium">Laba / Rugi Periode Berjalan</td>
                                <td class="py-2 text-right font-semibold {{ $labaRugi < 0 ? 'text-red-500' : 'text-green-600' }}">
                                    {{ number_format($labaRugi, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="font-bold border-t-2 border-slate-200">
                                <td colspan="2" class="py-2 text-slate-700">JUMLAH MODAL</td>
                                <td class="py-2 text-right text-slate-700">{{ number_format($totalModal, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Total Kewajiban & Modal --}}
                <div class="border-t-4 border-double border-slate-300 pt-4">
                    <div class="flex justify-between items-center font-extrabold text-lg text-slate-800">
                        <span>JUMLAH KEWAJIBAN & MODAL</span>
                        <span class="text-blue-600">{{ number_format($totalKewajiban + $totalModal, 0, ',', '.') }}</span>
                    </div>
                    @if(abs($totalAset - ($totalKewajiban + $totalModal)) > 1)
                        <div class="mt-2 text-xs text-red-500 font-semibold text-right">
                            ⚠️ Neraca tidak balance! Selisih: {{ number_format(abs($totalAset - ($totalKewajiban + $totalModal)), 0, ',', '.') }}
                        </div>
                    @else
                        <div class="mt-2 text-xs text-green-500 font-semibold text-right">
                            ✓ Neraca Balance
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
