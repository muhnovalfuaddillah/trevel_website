@extends('layouts.app')

@section('title', 'Perawatan Kendaraan')
@section('page_title', 'Perawatan & Servis Kendaraan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-display font-bold text-white">Log Maintenance & Service Bengkel</h2>
        <p class="text-xs text-slate-400">Catat riwayat pergantian sparepart, oli, servis mesin, AC, ban, dan estimasi biaya per perbaikan.</p>
    </div>
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/20 transition-all">
        <span class="material-symbols-outlined text-sm">build</span>
        Catat Maintenance
    </button>
</div>

<!-- Maintenances Table -->
<div class="glass-panel rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800">
                    <th class="pb-3 font-semibold">Tgl Perawatan</th>
                    <th class="pb-3 font-semibold">Kendaraan (Plat / Merk)</th>
                    <th class="pb-3 font-semibold">Jenis Perawatan</th>
                    <th class="pb-3 font-semibold">Tujuan</th>
                    <th class="pb-3 font-semibold">Biaya (Rp)</th>
                    <th class="pb-3 font-semibold">Catatan / Bengkel</th>
                    <th class="pb-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($maintenances as $m)
                <tr>
                    <td class="py-3.5 whitespace-nowrap">
                        <span class="font-semibold text-white block">{{ $m->tanggal_perawatan->format('d M Y') }}</span>
                    </td>
                    <td class="py-3.5">
                        <span class="text-white font-semibold block">{{ $m->vehicle->plat_nomor ?? '-' }}</span>
                        <span class="text-[11px] text-slate-400">{{ $m->vehicle->merk ?? '' }}</span>
                    </td>
                    <td class="py-3.5">
                        <div class="flex flex-wrap gap-1">
                            @if(is_array($m->jenis_perawatan))
                                @foreach($m->jenis_perawatan as $j)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-sky-300 text-[11px] border border-slate-700">
                                        {{ $j }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td class="py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $m->tujuan_perawatan === 'Perbaikan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                            {{ $m->tujuan_perawatan }}
                        </span>
                    </td>
                    <td class="py-3.5 font-bold text-rose-400">
                        Rp {{ number_format($m->biaya, 0, ',', '.') }}
                    </td>
                    <td class="py-3.5 text-slate-300 max-w-xs truncate">
                        {{ $m->catatan ?? '-' }}
                    </td>
                    <td class="py-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='openEditModal(@json($m))' class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span> Edit
                            </button>
                            <form action="{{ route('maintenances.destroy', $m->id) }}" method="POST" onsubmit="return confirmDelete(this, 'catatan servis bengkel {{ $m->vehicle->plat_nomor ?? '' }}');">
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
                    <td colspan="7" class="py-8 text-center text-slate-500">Belum ada riwayat maintenance perawatan kendaraan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Maintenance -->
<div id="maintenanceModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Catat Service Kendaraan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="maintenanceForm" method="POST" action="{{ route('maintenances.store') }}" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div>
                <label class="block font-medium text-slate-300 mb-1">Pilih Mobil Kendaraan</label>
                <select id="vehicle_id" name="vehicle_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}">{{ $v->plat_nomor }} - {{ $v->merk }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Tanggal Perawatan</label>
                <input type="date" id="tanggal_perawatan" name="tanggal_perawatan" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-2">Jenis Perawatan (Pilih minimal 1):</label>
                <div class="grid grid-cols-2 gap-2 bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="jenis_perawatan[]" value="Ganti oli" class="jenis-cb rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                        Ganti Oli
                    </label>
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="jenis_perawatan[]" value="Servis mesin" class="jenis-cb rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                        Servis Mesin
                    </label>
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="jenis_perawatan[]" value="Ganti ban" class="jenis-cb rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                        Ganti Ban
                    </label>
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="jenis_perawatan[]" value="Servis AC" class="jenis-cb rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                        Servis AC
                    </label>
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer col-span-2">
                        <input type="checkbox" name="jenis_perawatan[]" value="Lainnya" class="jenis-cb rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                        Lainnya (Perbaikan kelistrikan, rem, bodi, dll)
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Tujuan Perawatan</label>
                <select id="tujuan_perawatan" name="tujuan_perawatan" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="Rutin">Rutin (Periodic Service)</option>
                    <option value="Perbaikan">Perbaikan (Repair Breakdown)</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Total Biaya Maintenance (Rp)</label>
                <input type="number" id="biaya" name="biaya" min="0" placeholder="500000" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Catatan Detail / Nama Bengkel</label>
                <textarea id="catatan" name="catatan" rows="2" placeholder="Detail part diganti, rekomendasi bengkel..." class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-500 shadow-lg shadow-sky-600/20">Simpan Log</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Catat Service Kendaraan';
        document.getElementById('maintenanceForm').action = "{{ route('maintenances.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('vehicle_id').value = '';
        document.getElementById('tanggal_perawatan').value = '{{ date("Y-m-d") }}';
        document.getElementById('tujuan_perawatan').value = 'Rutin';
        document.getElementById('biaya').value = '';
        document.getElementById('catatan').value = '';
        
        document.querySelectorAll('.jenis-cb').forEach(cb => cb.checked = false);
        document.getElementById('maintenanceModal').classList.remove('hidden');
    }

    function openEditModal(m) {
        document.getElementById('modalTitle').innerText = 'Edit Service Kendaraan';
        document.getElementById('maintenanceForm').action = "/maintenances/" + m.id;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('vehicle_id').value = m.vehicle_id;
        document.getElementById('tanggal_perawatan').value = m.tanggal_perawatan.split('T')[0];
        document.getElementById('tujuan_perawatan').value = m.tujuan_perawatan;
        document.getElementById('biaya').value = m.biaya;
        document.getElementById('catatan').value = m.catatan || '';

        document.querySelectorAll('.jenis-cb').forEach(cb => {
            cb.checked = Array.isArray(m.jenis_perawatan) && m.jenis_perawatan.includes(cb.value);
        });

        document.getElementById('maintenanceModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('maintenanceModal').classList.add('hidden');
    }
</script>
@endsection
