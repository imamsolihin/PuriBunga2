@extends('layouts.admin')
@section('title', 'COA')
@section('page-title', 'COA / Akun Keuangan')
@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('admin.coa.create') }}" class="inline-flex items-center gap-2 bg-[#0f2557] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Akun
        </a>
    </div>
    <div class="card-stat overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Kode</th>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Nama Akun</th>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Tipe</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($coas as $coa)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-mono font-bold text-blue-700">{{ $coa->kode_akun }}</td>
                    <td class="py-3 px-4 font-semibold text-slate-700">{{ $coa->nama_akun }}</td>
                    <td class="py-3 px-4">
                        @php $tipeColor = ['aset'=>'blue','kewajiban'=>'red','ekuitas'=>'purple','pendapatan'=>'green','beban'=>'orange'][$coa->tipe] ?? 'slate'; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $tipeColor }}-100 text-{{ $tipeColor }}-700">{{ ucfirst($coa->tipe) }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.coa.edit', $coa) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.coa.destroy', $coa) }}" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-16 text-center text-slate-400">Belum ada data COA</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($coas->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $coas->links() }}</div>
        @endif
    </div>
</div>
@endsection
