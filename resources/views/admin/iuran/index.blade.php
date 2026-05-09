@extends('layouts.admin')
@section('title', 'Iuran Warga')
@section('page-title', 'Manajemen Iuran')
@section('content')
<div class="space-y-4">
    {{-- Filter --}}
    <div class="card-stat p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Bulan</label>
                <select name="bulan" class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                    <option value="{{ $b }}" {{ request('bulan') === $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun</label>
                <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="{{ date('Y') }}" class="border border-slate-200 rounded-xl px-3 py-2 text-sm w-28 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                <select name="status" class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option>
                </select>
            </div>
            <button type="submit" class="bg-[#0f2557] text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors">Filter</button>
            <a href="{{ route('admin.iuran.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2 rounded-xl text-sm">Reset</a>
        </form>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.iuran.create') }}" class="inline-flex items-center gap-2 bg-[#0f2557] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Iuran
        </a>
    </div>

    <div class="card-stat overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Warga</th>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Kategori</th>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Periode</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Nominal</th>
                        <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Status</th>
                        <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($iurans as $iuran)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-semibold text-slate-700">{{ $iuran->warga?->nama_lengkap ?? '-' }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $iuran->kategoriIuran?->nama_kategori ?? '-' }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $iuran->bulan }} {{ $iuran->tahun }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-slate-800">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $iuran->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($iuran->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.iuran.edit', $iuran) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.iuran.destroy', $iuran) }}" onsubmit="return confirm('Hapus data iuran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-16 text-center text-slate-400">Belum ada data iuran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($iurans->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $iurans->links() }}</div>
        @endif
    </div>
</div>
@endsection
