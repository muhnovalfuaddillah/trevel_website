@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')
@section('page_title', 'Pengeluaran Operasional')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-display font-bold text-white">Catatan Pengeluaran Operasional</h2>
        <p class="text-xs text-slate-400">Pencatatan pengeluaran harian seperti BBM, E-Toll, parkir, servis darurat, dan biaya operasional lainnya.</p>
    </div>
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/20 transition-all">
        <span class="material-symbols-outlined text-sm">post_add</span>
        Tambah Pengeluaran
    </button>
</div>

<!-- Expenses Table -->
<div class="glass-panel rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800">
                    <th class="pb-3 font-semibold">Tanggal</th>
                    <th class="pb-3 font-semibold">Kategori</th>
                    <th class="pb-3 font-semibold">Nominal Jumlah (Rp)</th>
                    <th class="pb-3 font-semibold">Terkait Perjalanan / Rute</th>
                    <th class="pb-3 font-semibold">Keterangan Catatan</th>
                    <th class="pb-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($expenses as $e)
                <tr>
                    <td class="py-3.5 whitespace-nowrap">
                        <span class="font-semibold text-white block">{{ $e->tanggal->format('d M Y') }}</span>
                    </td>
                    <td class="py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $e->kategori === 'BBM' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($e->kategori === 'Tol' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($e->kategori === 'Parkir' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20')) }}">
                            {{ $e->kategori }}
                        </span>
                    </td>
                    <td class="py-3.5 font-bold text-rose-400">
                        Rp {{ number_format($e->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="py-3.5">
                        @if($e->schedule)
                            <span class="text-slate-200 block font-medium">{{ $e->schedule->rute }}</span>
                            <span class="text-[11px] text-slate-400">Mobil: {{ $e->schedule->vehicle->plat_nomor ?? '-' }}</span>
                        @else
                            <span class="text-slate-500 italic">Pengeluaran Umum / Operasional</span>
                        @endif
                    </td>
                    <td class="py-3.5 text-slate-300 max-w-xs truncate">
                        {{ $e->keterangan ?? '-' }}
                    </td>
                    <td class="py-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='openEditModal(@json($e))' class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span> Edit
                            </button>
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" onsubmit="return confirmDelete(this, 'pengeluaran {{ $e->kategori }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-500">Belum ada catatan pengeluaran operasional.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Expense -->
<div id="expenseModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-700">
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
                <label class="block font-medium text-slate-300 mb-1">Tanggal Pengeluaran</label>
                <input type="date" id="tanggal" name="tanggal" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Kategori Pengeluaran</label>
                <select id="kategori" name="kategori" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="BBM">BBM (Bahan Bakar Minyak)</option>
                    <option value="Tol">Tol (E-Toll / Jalan Tol)</option>
                    <option value="Parkir">Parkir Pool / Rest Area</option>
                    <option value="Servis kendaraan">Servis Kendaraan Emergency</option>
                    <option value="Lainnya">Lainnya (Makan driver, tips, dll)</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Nominal Jumlah (Rp)</label>
                <input type="number" id="jumlah" name="jumlah" min="0" placeholder="150000" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Hubungkan ke Perjalanan (Opsional)</label>
                <select id="schedule_id" name="schedule_id" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Umum / Tanpa Rute Spesifik --</option>
                    @foreach($schedules as $s)
                        <option value="{{ $s->id }}">{{ $s->tanggal_keberangkatan->format('d/m/Y') }} - {{ $s->rute }} ({{ $s->vehicle->plat_nomor ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Keterangan Catatan</label>
                <input type="text" id="keterangan" name="keterangan" placeholder="Contoh: Isu BBM Pertamax di SPBU KM 19" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-500 shadow-lg shadow-sky-600/20">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Pengeluaran Operasional';
        document.getElementById('expenseForm').action = "{{ route('expenses.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('tanggal').value = '{{ date("Y-m-d") }}';
        document.getElementById('kategori').value = 'BBM';
        document.getElementById('jumlah').value = '';
        document.getElementById('schedule_id').value = '';
        document.getElementById('keterangan').value = '';
        document.getElementById('expenseModal').classList.remove('hidden');
    }

    function openEditModal(e) {
        document.getElementById('modalTitle').innerText = 'Edit Pengeluaran Operasional';
        document.getElementById('expenseForm').action = "/expenses/" + e.id;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('tanggal').value = e.tanggal.split('T')[0];
        document.getElementById('kategori').value = e.kategori;
        document.getElementById('jumlah').value = e.jumlah;
        document.getElementById('schedule_id').value = e.schedule_id || '';
        document.getElementById('keterangan').value = e.keterangan || '';
        document.getElementById('expenseModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('expenseModal').classList.add('hidden');
    }
</script>
@endsection
