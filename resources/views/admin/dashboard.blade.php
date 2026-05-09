@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Keuangan')

@section('content')
<div class="space-y-6">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card-stat p-5 border-l-4 border-green-500">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Pemasukan</div>
            <div class="text-2xl font-extrabold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="flex items-center gap-1 mt-2 text-xs text-green-500">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                Semua transaksi masuk
            </div>
        </div>
        <div class="card-stat p-5 border-l-4 border-red-500">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Pengeluaran</div>
            <div class="text-2xl font-extrabold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="flex items-center gap-1 mt-2 text-xs text-red-500">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Semua transaksi keluar
            </div>
        </div>
        <div class="card-stat p-5 border-l-4 border-blue-600">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Saldo Kas</div>
            <div class="text-2xl font-extrabold text-blue-700">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
            <div class="flex items-center gap-1 mt-2 text-xs text-blue-500">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.028 2.353 1.118V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.028-2.354-1.118V5z" clip-rule="evenodd"/></svg>
                Pemasukan - Pengeluaran
            </div>
        </div>
        <div class="card-stat p-5 border-l-4 border-purple-500">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Warga</div>
            <div class="text-2xl font-extrabold text-purple-700">{{ $totalWarga }}</div>
            <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                <span class="text-green-500 font-medium">{{ $iuranLunas }} lunas</span> •
                <span class="text-yellow-500 font-medium">{{ $iuranBelum }} belum</span>
            </div>
        </div>
    </div>

    {{-- Chart + Status --}}
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card-stat p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Grafik Keuangan 6 Bulan Terakhir</h3>
                <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-medium">Pemasukan vs Pengeluaran</span>
            </div>
            <canvas id="keuanganChart" height="110"></canvas>
        </div>
        <div class="card-stat p-6">
            <h3 class="font-bold text-slate-800 mb-4">Status Iuran</h3>
            <div class="relative flex items-center justify-center mb-4">
                <canvas id="iuranChart" width="180" height="180"></canvas>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span><span class="text-slate-600">Lunas</span></div>
                    <span class="font-bold text-green-600">{{ $iuranLunas }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span><span class="text-slate-600">Belum Lunas</span></div>
                    <span class="font-bold text-yellow-600">{{ $iuranBelum }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi Terbaru --}}
    <div class="card-stat p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
            <a href="{{ route('admin.jurnal.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Tanggal</th>
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Keterangan</th>
                        <th class="text-left py-2 px-3 text-slate-500 font-semibold text-xs">Tipe</th>
                        <th class="text-right py-2 px-3 text-slate-500 font-semibold text-xs">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $t)
                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                        <td class="py-2.5 px-3 text-slate-600">{{ $t->tanggal->format('d M Y') }}</td>
                        <td class="py-2.5 px-3 text-slate-700 font-medium">{{ Str::limit($t->keterangan, 40) }}</td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $t->tipe_transaksi === 'pemasukan' ? 'bg-green-100 text-green-700' : ($t->tipe_transaksi === 'pengeluaran' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ ucfirst($t->tipe_transaksi) }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-right font-semibold {{ $t->tipe_transaksi === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-slate-400">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Bar Chart
const chartData = @json($chartData);
const ctx = document.getElementById('keuanganChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.bulan),
        datasets: [
            {
                label: 'Pemasukan',
                data: chartData.map(d => d.pemasukan),
                backgroundColor: 'rgba(34,197,94,0.7)',
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'Pengeluaran',
                data: chartData.map(d => d.pengeluaran),
                backgroundColor: 'rgba(239,68,68,0.7)',
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000).toLocaleString('id') + 'K' } }
        }
    }
});
// Doughnut Chart
const ctx2 = document.getElementById('iuranChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Lunas', 'Belum Lunas'],
        datasets: [{
            data: [{{ $iuranLunas }}, {{ $iuranBelum }}],
            backgroundColor: ['#22c55e', '#facc15'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: { responsive: false, plugins: { legend: { display: false } }, cutout: '70%' }
});
</script>
@endsection
