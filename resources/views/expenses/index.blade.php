@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')
@section('page_title', 'Pengeluaran Operasional')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Catatan Pengeluaran Operasional</h2>
        <p class="text-xs text-slate-400 mt-0.5">Pencatatan pengeluaran harian seperti BBM, E-Toll, parkir, servis darurat, dan biaya operasional lainnya.</p>
    </div>
    <div class="flex items-center gap-2 self-start md:self-auto">
        @if(auth()->check() && auth()->user()->isOwner())
        <a href="{{ route('reports.expenses') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-sky-400 border border-slate-700 font-semibold text-xs flex items-center gap-2 transition-all">
            <span class="material-symbols-outlined text-base text-sky-400">analytics</span>
            Laporan Pengeluaran
        </a>
        @endif
        <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all">
            <span class="material-symbols-outlined text-base">post_add</span>
            Tambah Pengeluaran
        </button>
    </div>
</div>

<!-- Expenses Table -->
<div class="glass-panel rounded-3xl p-4 sm:p-6 mt-6 border border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs min-w-[650px]">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800/80">
                    <th class="pb-3.5 font-semibold">Tanggal</th>
                    <th class="pb-3.5 font-semibold">Kategori</th>
                    <th class="pb-3.5 font-semibold text-rose-400">Nominal Jumlah (Rp)</th>
                    <th class="pb-3.5 font-semibold">Terkait Perjalanan / Rute</th>
                    <th class="pb-3.5 font-semibold">Keterangan Catatan</th>
                    <th class="pb-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($expenses as $e)
                <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <span class="font-bold text-white block text-xs">{{ $e->tanggal->format('d M Y') }}</span>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                            {{ $e->kategori === 'BBM' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($e->kategori === 'Tol' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($e->kategori === 'Parkir' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20')) }}">
                            {{ $e->kategori }}
                        </span>
                    </td>
                    <td class="py-4 font-mono font-bold text-rose-400 text-sm whitespace-nowrap">
                        Rp {{ number_format($e->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="py-4">
                        @if($e->schedule)
                            <span class="text-slate-200 block font-bold text-xs">{{ $e->schedule->rute }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">Mobil: {{ $e->schedule->vehicle->plat_nomor ?? '-' }}</span>
                        @else
                            <span class="text-slate-500 italic text-[11px]">Pengeluaran Umum / Operasional</span>
                        @endif
                    </td>
                    <td class="py-4 text-slate-300 max-w-xs truncate text-xs">
                        {{ $e->keterangan ?? '-' }}
                    </td>
                    <td class="py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='openEditModal(@json($e))' class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all flex items-center gap-1" title="Edit Pengeluaran">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" onsubmit="return confirmDelete(this, 'pengeluaran {{ $e->kategori }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center gap-1" title="Hapus Pengeluaran">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-slate-500 font-medium">Belum ada catatan pengeluaran operasional.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Expense -->
<div id="expenseModal" onclick="if(event.target === this) closeModal()" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tambah Pengeluaran</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="expenseForm" method="POST" action="{{ route('expenses.store') }}" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Tanggal Pengeluaran</label>
                <input type="date" id="tanggal" name="tanggal" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Kategori Pengeluaran</label>
                <select id="kategori" name="kategori" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="BBM">BBM (Bahan Bakar)</option>
                    <option value="Tol">Tol (E-Toll)</option>
                    <option value="Parkir">Parkir</option>
                    <option value="Servis kendaraan">Servis Kendaraan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Nominal Jumlah Pengeluaran (Rp)</label>
                <input type="text" inputmode="numeric" id="jumlah" name="jumlah" placeholder="Contoh: 150.000" required class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-rose-400 font-bold font-mono focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Terkait Perjalanan / Schedule (Opsional)</label>
                <select id="schedule_id" name="schedule_id" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pengeluaran Umum (Tanpa Schedule Khusus) --</option>
                    @foreach($schedules as $s)
                        <option value="{{ $s->id }}">{{ $s->rute }} (Mobil: {{ $s->vehicle->plat_nomor ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Keterangan Catatan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: BBM Pertamina Dex di rest area KM 57" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-sky-600 text-white font-bold hover:bg-sky-500 shadow-lg shadow-sky-600/30">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Pengeluaran';
        document.getElementById('expenseForm').action = "{{ route('expenses.store') }}";
        document.getElementById('methodField').value = 'POST';

        document.getElementById('tanggal').value = "{{ date('Y-m-d') }}";
        document.getElementById('kategori').value = 'BBM';
        setCurrencyInputValue(document.getElementById('jumlah'), '');
        document.getElementById('schedule_id').value = '';
        document.getElementById('keterangan').value = '';

        document.getElementById('expenseModal').classList.remove('hidden');
    }

    function openEditModal(expense) {
        document.getElementById('modalTitle').innerText = 'Edit Pengeluaran';
        document.getElementById('expenseForm').action = "/expenses/" + expense.id;
        document.getElementById('methodField').value = 'PUT';

        document.getElementById('tanggal').value = expense.tanggal.substring(0, 10);
        document.getElementById('kategori').value = expense.kategori;
        setCurrencyInputValue(document.getElementById('jumlah'), expense.jumlah);
        document.getElementById('schedule_id').value = expense.schedule_id || '';
        document.getElementById('keterangan').value = expense.keterangan || '';

        document.getElementById('expenseModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('expenseModal').classList.add('hidden');
    }
</script>
@endsection
