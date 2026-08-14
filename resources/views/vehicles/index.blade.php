@extends('layouts.app')

@section('title', 'Data Kendaraan Travel')
@section('page_title', 'Data Kendaraan & Armada')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Kelola Armada Mobil Travel</h2>
        <p class="text-xs text-slate-400 mt-0.5">Daftar armada mobil travel, kapasitas tempat duduk, tarif sewa per hari, dan status operasional.</p>
    </div>
    @if(auth()->check() && auth()->user()->isOwner())
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all self-start md:self-auto">
        <span class="material-symbols-outlined text-base">add_circle</span>
        Tambah Kendaraan
    </button>
    @else
    <div class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 text-xs flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-base text-amber-400">visibility</span> Modus Lihat Data (Read-Only Supir)
    </div>
    @endif
</div>

<!-- Vehicle Cards / Table Grid -->
<div class="glass-panel rounded-3xl p-4 sm:p-6 mt-6 border border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs min-w-[550px]">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800/80">
                    <th class="pb-3.5 font-semibold">Plat Nomor</th>
                    <th class="pb-3.5 font-semibold">Merk / Type Kendaraan</th>
                    <th class="pb-3.5 font-semibold">Kapasitas Kursi</th>
                    <th class="pb-3.5 font-semibold">Status Kendaraan</th>
                    <th class="pb-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($vehicles as $vehicle)
                <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-4 font-bold text-white whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-xl bg-slate-900 text-sky-400 font-mono font-bold text-xs tracking-wider border border-slate-800">
                                {{ $vehicle->plat_nomor }}
                            </span>
                        </div>
                    </td>
                    <td class="py-4 font-bold text-white text-xs">
                        {{ $vehicle->merk }}
                    </td>
                    <td class="py-4 text-slate-300">
                        <span class="px-3 py-1 rounded-xl bg-slate-900 text-slate-200 border border-slate-800 font-semibold text-xs">
                            {{ $vehicle->kapasitas }} Seat (Kursi)
                        </span>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase
                            {{ $vehicle->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($vehicle->status === 'Beroperasi' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            {{ $vehicle->status }}
                        </span>
                    </td>
                    <td class="py-4 text-right whitespace-nowrap">
                        @if(auth()->check() && auth()->user()->isOwner())
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(@json($vehicle))' class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all flex items-center gap-1" title="Edit Kendaraan">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirmDelete(this, 'armada kendaraan {{ $vehicle->plat_nomor }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center gap-1" title="Hapus Kendaraan">
                                        <span class="material-symbols-outlined text-sm">delete</span>
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
                    <td colspan="5" class="py-10 text-center text-slate-500 font-medium">Belum ada kendaraan yang didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->check() && auth()->user()->isOwner())
<!-- Modal Tambah/Edit Kendaraan (Hanya untuk Owner) -->
<div id="vehicleModal" onclick="if(event.target === this) closeModal()" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
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
                <label class="block font-semibold text-slate-300 mb-1.5">Plat Nomor Kendaraan</label>
                <input type="text" id="plat_nomor" name="plat_nomor" placeholder="Contoh: B 1234 ABC" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white font-mono font-bold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Merk / Type Mobil</label>
                <input type="text" id="merk" name="merk" placeholder="Contoh: Toyota HiAce Commuter 2.5" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Kapasitas Tempat Duduk</label>
                <input type="number" id="kapasitas" name="kapasitas" min="1" placeholder="Misal: 14" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Status Kendaraan</label>
                <select id="status" name="status" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="Tersedia">Tersedia (Ready di Pool)</option>
                    <option value="Beroperasi">Beroperasi (Dalam Perjalanan)</option>
                    <option value="Servis">Servis / Perawatan Bengkel</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold hover:from-sky-500 hover:to-indigo-500 shadow-lg shadow-sky-600/30">Simpan Armada</button>
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
        document.getElementById('kapasitas').value = '';
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
        document.getElementById('status').value = vehicle.status;

        document.getElementById('vehicleModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('vehicleModal').classList.add('hidden');
    }
</script>
@endif
@endsection
