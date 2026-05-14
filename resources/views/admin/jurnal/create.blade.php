@extends('layouts.admin')
@section('title', 'Catat Jurnal')
@section('page-title', 'Catat Jurnal Umum')
@section('content')
<div class="max-w-3xl">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.jurnal.store') }}" class="space-y-5"
              x-data="{
                tipe: '{{ old('tipe_transaksi', 'umum') }}',
                details: [{ coa_id: '', debit: 0, kredit: 0 }, { coa_id: '', debit: 0, kredit: 0 }],
                get totalDebit() { return this.details.reduce((s,d)=>s+(parseFloat(d.debit)||0),0); },
                get totalKredit() { return this.details.reduce((s,d)=>s+(parseFloat(d.kredit)||0),0); },
                get balanced() { 
                  const diff = Math.abs(this.totalDebit - this.totalKredit);
                  return diff < 0.01 && this.totalDebit > 0; 
                }
              }">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Transaksi <span class="text-red-500">*</span></label>
                    <select name="tipe_transaksi" x-model="tipe" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-bold text-slate-700">Detail Jurnal (Double Entry)</label>
                    <button type="button" @click="details.push({coa_id:'',debit:0,kredit:0})" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">+ Tambah Baris</button>
                </div>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left py-2 px-3 text-xs text-slate-500 font-semibold">Akun COA</th>
                                <th class="text-right py-2 px-3 text-xs text-slate-500 font-semibold w-32">Debit (Rp)</th>
                                <th class="text-right py-2 px-3 text-xs text-slate-500 font-semibold w-32">Kredit (Rp)</th>
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
                <p x-show="!balanced && totalDebit > 0" class="text-yellow-600 text-xs mt-1 font-medium">⚠ Debit dan Kredit harus seimbang</p>
                <p x-show="balanced" class="text-green-600 text-xs mt-1 font-medium">✓ Jurnal seimbang</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" 
                        :disabled="!balanced"
                        :class="balanced ? 'bg-[#0f2557] hover:bg-[#1a3a8f] cursor-pointer' : 'bg-slate-300 cursor-not-allowed'"
                        class="text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all shadow">
                    Simpan Jurnal
                </button>
                <a href="{{ route('admin.jurnal.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
