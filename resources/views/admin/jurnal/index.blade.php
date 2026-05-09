@extends('layouts.admin')
@section('title', 'Jurnal Umum')
@section('page-title', 'Jurnal Umum')
@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('admin.jurnal.create') }}" class="inline-flex items-center gap-2 bg-[#0f2557] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Catat Jurnal
        </a>
    </div>
    <div class="card-stat overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Keterangan</th>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Tipe</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Total</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($jurnals as $jurnal)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 text-slate-600">{{ $jurnal->tanggal->format('d M Y') }}</td>
                    <td class="py-3 px-4 font-medium text-slate-700">{{ Str::limit($jurnal->keterangan, 40) }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $jurnal->tipe_transaksi === 'pemasukan' ? 'bg-green-100 text-green-700' : ($jurnal->tipe_transaksi === 'pengeluaran' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ ucfirst($jurnal->tipe_transaksi) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right font-semibold text-slate-800">Rp {{ number_format($jurnal->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jurnal.show', $jurnal) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.jurnal.edit', $jurnal) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.jurnal.destroy', $jurnal) }}" onsubmit="return confirm('Hapus jurnal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-16 text-center text-slate-400">Belum ada jurnal</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($jurnals->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $jurnals->links() }}</div>
        @endif
    </div>
</div>
@endsection
