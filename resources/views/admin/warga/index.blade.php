@extends('layouts.admin')
@section('title', 'Data Warga')
@section('page-title', 'Manajemen Warga')
@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <p class="text-slate-500 text-sm">Total <strong class="text-slate-700">{{ $wargas->total() }}</strong> warga terdaftar</p>
        <a href="{{ route('admin.warga.create') }}" class="inline-flex items-center gap-2 bg-[#0f2557] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Warga
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.warga.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, blok, atau nomor rumah..." class="flex-1 min-w-0 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0f2557] focus:border-transparent">
            <button type="submit" class="bg-[#0f2557] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#1a3a8f] transition-colors">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.warga.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-300 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="card-stat overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider">Nama</th>
                        <th class="text-left py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider">Blok/No</th>
                        <th class="text-left py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">No HP</th>
                        <th class="text-left py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden lg:table-cell">Email Akun</th>
                        <th class="text-left py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-4 text-slate-500 font-semibold text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($wargas as $warga)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($warga->nama_lengkap, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $warga->nama_lengkap }}</div>
                                    <div class="text-xs text-slate-500">Penghuni: {{ $warga->penghunis->pluck('nama')->implode(', ') ?: '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-medium">{{ $warga->blok_rumah }}/{{ $warga->nomor_rumah }}</td>
                        <td class="py-3 px-4 text-slate-600 hidden md:table-cell">{{ $warga->no_hp ?? '-' }}</td>
                        <td class="py-3 px-4 text-slate-600 hidden lg:table-cell">{{ $warga->user?->email ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $warga->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($warga->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.warga.show', $warga) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition-colors" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.warga.edit', $warga) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.warga.destroy', $warga) }}" onsubmit="return confirm('Hapus warga ini? Akun login juga akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-16 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        Belum ada data warga
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($wargas->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $wargas->links() }}</div>
        @endif
    </div>
</div>
@endsection
