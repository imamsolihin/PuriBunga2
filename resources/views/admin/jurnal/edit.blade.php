@extends('layouts.admin')
@section('title', 'Edit Jurnal')
@section('page-title', 'Edit Jurnal Umum')
@section('content')
<div class="max-w-3xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.jurnal.update', $jurnal) }}" class="space-y-5"
              x-data="{
                details: {{ json_encode($jurnal->details->map(fn($d)=>['coa_id'=>$d->coa_id,'debit'=>$d->debit,'kredit'=>$d->kredit])) }},
                get totalDebit() { return this.details.reduce((s,d)=>s+parseFloat(d.debit||0),0); },
                get totalKredit() { return this.details.reduce((s,d)=>s+parseFloat(d.kredit||0),0); },
                get balanced() { return this.totalDebit === this.totalKredit && this.totalDebit > 0; }
              }">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $jurnal->tanggal->format('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Transaksi</label>
                    <select name="tipe_transaksi" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach(['pemasukan','pengeluaran','umum'] as $t)
                        <option value="{{ $t }}" {{ $jurnal->tipe_transaksi === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan', $jurnal->keterangan) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-bold text-slate-700">Detail Jurnal</label>
                    <button type="button" @click="details.push({coa_id:'',debit:0,kredit:0})" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">+ Tambah Baris</button>
                </div>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left py-2 px-3 text-xs text-slate-500 font-semibold">Akun COA</th>
                                <th class="text-right py-2 px-3 text-xs text-slate-500 font-semibold w-32">Debit</th>
                                <th class="text-right py-2 px-3 text-xs text-slate-500 font-semibold w-32">Kredit</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(detail, i) in details" :key="i">
                                <tr class="border-t border-slate-100">
                                    <td class="py-2 px-3">
                                        <select :name="`details[${i}][coa_id]`" x-model="detail.coa_id" class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Pilih Akun...</option>
                                            @foreach($coas as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="number" :name="`details[${i}][debit]`" x-model="detail.debit" class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" step="0.01">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="number" :name="`details[${i}][kredit]`" x-model="detail.kredit" class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" step="0.01">
                                    </td>
                                    <td class="py-2 px-1">
                                        <button type="button" @click="details.splice(i,1)" x-show="details.length > 2" class="text-red-400 hover:text-red-600 p-1 rounded">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200">
                            <tr>
                                <td class="py-2 px-3 text-xs font-bold text-slate-600">Total</td>
                                <td class="py-2 px-3 text-right text-xs font-bold" :class="balanced ? 'text-green-600' : 'text-red-600'" x-text="'Rp ' + totalDebit.toLocaleString('id')"></td>
                                <td class="py-2 px-3 text-right text-xs font-bold" :class="balanced ? 'text-green-600' : 'text-red-600'" x-text="'Rp ' + totalKredit.toLocaleString('id')"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">Update Jurnal</button>
                <a href="{{ route('admin.jurnal.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
