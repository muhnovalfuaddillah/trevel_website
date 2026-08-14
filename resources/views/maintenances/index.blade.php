@extends('layouts.app')

@section('title', 'Perawatan Kendaraan')
@section('page_title', 'Perawatan & Servis Kendaraan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Log Maintenance & Service Bengkel</h2>
        <p class="text-xs text-slate-400 mt-0.5">Catat riwayat pergantian sparepart, oli, servis mesin, AC, ban, dan estimasi biaya per perbaikan.</p>
    </div>
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all self-start md:self-auto">
        <span class="material-symbols-outlined text-base">build</span>
        Catat Maintenance
    </button>
</div>

<!-- Maintenances Table -->
<div class="glass-panel rounded-3xl p-4 sm:p-6 mt-6 border border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs min-w-[750px]">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800/80">
                    <th class="pb-3.5 font-semibold">Tgl Perawatan</th>
                    <th class="pb-3.5 font-semibold">Kendaraan (Plat / Merk)</th>
                    <th class="pb-3.5 font-semibold">Petugas / Penanggung Jawab</th>
                    <th class="pb-3.5 font-semibold">Jenis Perawatan</th>
                    <th class="pb-3.5 font-semibold">Tujuan</th>
                    <th class="pb-3.5 font-semibold text-rose-400">Biaya (Rp)</th>
                    <th class="pb-3.5 font-semibold text-amber-400">Kilometer (KM)</th>
                    <th class="pb-3.5 font-semibold">Foto Bukti / Nota</th>
                    <th class="pb-3.5 font-semibold">Catatan / Bengkel</th>
                    <th class="pb-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($maintenances as $m)
                <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <span class="font-bold text-white block text-xs">{{ $m->tanggal_perawatan->format('d M Y') }}</span>
                    </td>
                    <td class="py-4">
                        <span class="px-2.5 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800 inline-block">
                            {{ $m->vehicle->plat_nomor ?? '-' }}
                        </span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ $m->vehicle->merk ?? '' }}</span>
                    </td>
                    <td class="py-4">
                        <span class="px-2.5 py-1 rounded-xl bg-slate-900 text-amber-300 font-bold border border-slate-800 text-[11px] inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">person</span>
                            {{ $m->petugas_perawatan ?? 'Owner' }}
                        </span>
                    </td>
                    <td class="py-4">
                        <div class="flex flex-wrap gap-1">
                            @if(is_array($m->jenis_perawatan))
                                @foreach($m->jenis_perawatan as $j)
                                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-sky-300 text-[10px] font-semibold border border-slate-800">
                                        {{ $j }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase
                            {{ $m->tujuan_perawatan === 'Perbaikan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                            {{ $m->tujuan_perawatan }}
                        </span>
                    </td>
                    <td class="py-4 font-mono font-bold text-rose-400 text-sm whitespace-nowrap">
                        Rp {{ number_format($m->biaya, 0, ',', '.') }}
                    </td>
                    <td class="py-4 font-mono font-bold text-amber-400 text-xs whitespace-nowrap">
                        {{ $m->kilometer ? number_format($m->kilometer, 0, ',', '.') . ' KM' : '-' }}
                    </td>
                    <td class="py-4">
                        @if(!empty($m->foto_bukti) && is_array($m->foto_bukti) && count($m->foto_bukti) > 0)
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @foreach($m->foto_bukti as $img)
                                    <a href="{{ asset($img) }}" target="_blank" class="block w-9 h-9 rounded-xl overflow-hidden border border-slate-700 hover:border-sky-400 transition-all shrink-0 shadow-sm" title="Lihat Foto Bukti">
                                        <img src="{{ asset($img) }}" alt="Foto Bukti Perawatan" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <span class="text-slate-600 italic text-[11px]">Tanpa Foto</span>
                        @endif
                    </td>
                    <td class="py-4 text-slate-300 max-w-xs truncate text-xs">
                        {{ $m->catatan ?? '-' }}
                    </td>
                    <td class="py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='openEditModal(@json($m))' class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all flex items-center gap-1" title="Edit Service">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('maintenances.destroy', $m->id) }}" method="POST" onsubmit="return confirmDelete(this, 'catatan servis bengkel {{ $m->vehicle->plat_nomor ?? '' }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center gap-1" title="Hapus Service">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-10 text-center text-slate-500 font-medium">Belum ada riwayat maintenance perawatan kendaraan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Maintenance -->
<div id="maintenanceModal" onclick="if(event.target === this) closeModal()" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Catat Service Kendaraan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="maintenanceForm" method="POST" action="{{ route('maintenances.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Pilih Armada Mobil Kendaraan</label>
                <select id="vehicle_id" name="vehicle_id" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach($vehicles as $v)
                        @php
                            $assignedDriver = $v->schedules->first()->driver->nama ?? '';
                        @endphp
                        <option value="{{ $v->id }}" data-driver="{{ $assignedDriver ? 'Sopir: ' . $assignedDriver : '' }}">
                            {{ $v->plat_nomor }} - {{ $v->merk }} {{ $assignedDriver ? '(Sopir: ' . $assignedDriver . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                    <span>Petugas / Penanggung Jawab Perawatan</span>
                    <span class="text-[10px] text-sky-400 font-normal">Sopir / Owner</span>
                </label>
                <select id="petugas_perawatan" name="petugas_perawatan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="Owner ({{ auth()->user()->name ?? 'Admin' }})">Owner ({{ auth()->user()->name ?? 'Admin' }})</option>
                    @foreach($drivers as $d)
                        <option value="Sopir: {{ $d->nama }}">Sopir: {{ $d->nama }} ({{ $d->nomor_hp ?? 'Driver' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Tanggal Servis / Perawatan</label>
                <input type="date" id="tanggal_perawatan" name="tanggal_perawatan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Jenis Perawatan (Pilih Lebih Dari Satu)</label>
                <div class="grid grid-cols-2 gap-2 p-3 rounded-2xl bg-slate-900 border border-slate-800">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Ganti Oli" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Ganti Oli
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Servis Mesin" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Servis Mesin
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Ganti Ban" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Ganti Ban
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Servis AC" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Servis AC
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Rem & Kampas" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Rem & Kampas
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Perawatan Body" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Perawatan Body
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="jenis_perawatan[]" value="Lainnya" class="jenis-cb rounded bg-slate-950 border-slate-700 text-sky-500"> Lainnya
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Tujuan Perawatan</label>
                <select id="tujuan_perawatan" name="tujuan_perawatan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="Rutin">Rutin (Perawatan Berkala / Periodic)</option>
                    <option value="Perbaikan">Perbaikan (Kondisi Rusak / Repair)</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Biaya Perawatan / Servis (Rp)</label>
                <input type="text" inputmode="numeric" id="biaya" name="biaya" placeholder="Nominal Biaya (Contoh: 1.500.000)" required class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-rose-400 font-mono font-bold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                    <span>Kilometer Kendaraan Saat Servis (Opsional)</span>
                    <span class="text-[10px] text-amber-400 font-normal">KM / Odometer</span>
                </label>
                <input type="text" inputmode="numeric" id="kilometer" name="kilometer" placeholder="Contoh: 125.000 (Bolehkosong)" class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-amber-400 font-mono font-bold focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                    <span>Upload Foto Bukti / Nota (Bisa Pilih 1 atau Lebih)</span>
                    <span class="text-[10px] text-emerald-400 font-normal">Multi-Upload JPG/PNG</span>
                </label>
                <input type="file" id="foto_bukti" name="foto_bukti[]" multiple accept="image/*" class="w-full px-3 py-2 rounded-2xl bg-slate-900 border border-slate-700 text-slate-300 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-600 file:text-white hover:file:bg-sky-500 text-xs">
                <p class="text-[10px] text-slate-500 mt-1">Tekan Ctrl / Shift saat memilih file untuk mengupload beberapa foto nota/kondisi sekaligus.</p>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Catatan / Nama Bengkel (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: Ganti oli Shell Helix di Bengkel Resmi Toyota" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold hover:from-sky-500 hover:to-indigo-500 shadow-lg shadow-sky-600/30">Simpan Log</button>
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
        document.getElementById('petugas_perawatan').value = "Owner ({{ auth()->user()->name ?? 'Admin' }})";
        document.getElementById('tanggal_perawatan').value = "{{ date('Y-m-d') }}";
        document.querySelectorAll('.jenis-cb').forEach(cb => cb.checked = false);
        document.getElementById('tujuan_perawatan').value = 'Rutin';
        setCurrencyInputValue(document.getElementById('biaya'), '');
        setCurrencyInputValue(document.getElementById('kilometer'), '');
        document.getElementById('catatan').value = '';
        document.getElementById('foto_bukti').value = '';

        document.getElementById('maintenanceModal').classList.remove('hidden');
    }

    function openEditModal(m) {
        document.getElementById('modalTitle').innerText = 'Edit Service Kendaraan';
        document.getElementById('maintenanceForm').action = "/maintenances/" + m.id;
        document.getElementById('methodField').value = 'PUT';

        document.getElementById('vehicle_id').value = m.vehicle_id;
        document.getElementById('petugas_perawatan').value = m.petugas_perawatan || "Owner ({{ auth()->user()->name ?? 'Admin' }})";
        if (m.tanggal_perawatan) {
            document.getElementById('tanggal_perawatan').value = m.tanggal_perawatan.substring(0, 10);
        }

        document.querySelectorAll('.jenis-cb').forEach(cb => {
            if (Array.isArray(m.jenis_perawatan) && m.jenis_perawatan.includes(cb.value)) {
                cb.checked = true;
            } else {
                cb.checked = false;
            }
        });

        document.getElementById('tujuan_perawatan').value = m.tujuan_perawatan;
        setCurrencyInputValue(document.getElementById('biaya'), m.biaya);
        setCurrencyInputValue(document.getElementById('kilometer'), m.kilometer || '');
        document.getElementById('catatan').value = m.catatan || '';
        document.getElementById('foto_bukti').value = '';

        document.getElementById('maintenanceModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('maintenanceModal').classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const vehicleSelect = document.getElementById('vehicle_id');
        if (vehicleSelect) {
            vehicleSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const driverVal = selectedOpt ? selectedOpt.getAttribute('data-driver') : '';
                const petugasSelect = document.getElementById('petugas_perawatan');
                if (driverVal) {
                    let found = false;
                    for (let i = 0; i < petugasSelect.options.length; i++) {
                        if (petugasSelect.options[i].value === driverVal) {
                            petugasSelect.selectedIndex = i;
                            found = true;
                            break;
                        }
                    }
                    if (!found) {
                        petugasSelect.value = "Owner ({{ auth()->user()->name ?? 'Admin' }})";
                    }
                } else if (this.value) {
                    petugasSelect.value = "Owner ({{ auth()->user()->name ?? 'Admin' }})";
                }
            });
        }
    });
</script>
@endsection
