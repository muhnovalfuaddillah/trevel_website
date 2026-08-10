@extends('layouts.app')

@section('title', 'Data Kendaraan Travel')
@section('page_title', 'Data Kendaraan & Armada')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-display font-bold text-white">Kelola Armada Mobil</h2>
        <p class="text-xs text-slate-400">Daftar armada mobil travel, kapasitas tempat duduk, tarif sewa per hari, dan status operasional.</p>
    </div>
    @if(auth()->check() && auth()->user()->isOwner())
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/20 transition-all">
        <span class="material-symbols-outlined text-sm">add</span>
        Tambah Kendaraan
    </button>
    @else
    <div class="px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 text-xs flex items-center gap-1.5 font-medium">
        <span class="material-symbols-outlined text-sm text-amber-400">visibility</span> Modus Lihat Data (Read-Only Supir)
    </div>
    @endif
</div>

<!-- Vehicle Cards / Table Grid -->
<div class="glass-panel rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800">
                    <th class="pb-3 font-semibold">Plat Nomor</th>
                    <th class="pb-3 font-semibold">Merk / Type Kendaraan</th>
                    <th class="pb-3 font-semibold">Kapasitas Tempat Duduk</th>
                    <th class="pb-3 font-semibold text-emerald-400">Tarif per Hari (Rp/Mobil)</th>
                    <th class="pb-3 font-semibold">Status Kendaraan</th>
                    <th class="pb-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($vehicles as $vehicle)
                <tr>
                    <td class="py-3.5 font-bold text-white whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded bg-slate-800 text-sky-400 font-mono tracking-wider border border-slate-700">
                                {{ $vehicle->plat_nomor }}
                            </span>
                        </div>
                    </td>
                    <td class="py-3.5 font-medium text-slate-200">
                        {{ $vehicle->merk }}
                    </td>
                    <td class="py-3.5 text-slate-300">
                        <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-200 border border-slate-700 font-semibold">
                            {{ $vehicle->kapasitas }} Kursi (Seat)
                        </span>
                    </td>
                    <td class="py-3.5 font-bold text-emerald-400 text-sm">
                        Rp {{ number_format($vehicle->tarif_per_hari, 0, ',', '.') }} / Hari
                    </td>
                    <td class="py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $vehicle->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($vehicle->status === 'Beroperasi' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            {{ $vehicle->status }}
                        </span>
                    </td>
                    <td class="py-3.5 text-right whitespace-nowrap">
                        @if(auth()->check() && auth()->user()->isOwner())
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(@json($vehicle))' class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                                </button>
                                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirmDelete(this, 'armada kendaraan {{ $vehicle->plat_nomor }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-slate-500 text-[11px] font-medium italic">Read-Only</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-500">Belum ada kendaraan yang didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->check() && auth()->user()->isOwner())
<!-- Modal Tambah/Edit Kendaraan (Hanya untuk Owner) -->
<div id="vehicleModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tambah Kendaraan Armada</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="vehicleForm" method="POST" action="{{ route('vehicles.store') }}" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div>
                <label class="block font-medium text-slate-300 mb-1">Plat Nomor Kendaraan</label>
                <input type="text" id="plat_nomor" name="plat_nomor" placeholder="Contoh: B 7890 TRV" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Merk & Type Mobil</label>
                <input type="text" id="merk" name="merk" placeholder="Contoh: Toyota HiAce Premio" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-300 mb-1">Kapasitas Kursi</label>
                    <input type="number" id="kapasitas" name="kapasitas" min="1" placeholder="12" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Tarif per Hari (Rp)</label>
                    <input type="number" id="tarif_per_hari" name="tarif_per_hari" min="0" placeholder="1750000" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Status Kendaraan</label>
                <select id="status" name="status" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="Tersedia">Tersedia (Ready)</option>
                    <option value="Beroperasi">Beroperasi (Sedang Jalan)</option>
                    <option value="Servis">Servis Bengkel</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-500 shadow-lg shadow-sky-600/20">Simpan Armada</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('scripts')
@if(auth()->check() && auth()->user()->isOwner())
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Kendaraan Armada';
        document.getElementById('vehicleForm').action = "{{ route('vehicles.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('plat_nomor').value = '';
        document.getElementById('merk').value = '';
        document.getElementById('kapasitas').value = '12';
        document.getElementById('tarif_per_hari').value = '1750000';
        document.getElementById('status').value = 'Tersedia';
        document.getElementById('vehicleModal').classList.remove('hidden');
    }

    function openEditModal(vehicle) {
        document.getElementById('modalTitle').innerText = 'Edit Kendaraan Armada';
        document.getElementById('vehicleForm').action = "/vehicles/" + vehicle.id;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('plat_nomor').value = vehicle.plat_nomor;
        document.getElementById('merk').value = vehicle.merk;
        document.getElementById('kapasitas').value = vehicle.kapasitas;
        document.getElementById('tarif_per_hari').value = vehicle.tarif_per_hari || 1500000;
        document.getElementById('status').value = vehicle.status;
        document.getElementById('vehicleModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('vehicleModal').classList.add('hidden');
    }
</script>
@endif
@endsection
