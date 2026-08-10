@extends('layouts.app')

@section('title', 'Jadwal Perjalanan')
@section('page_title', 'Jadwal Perjalanan & Keberangkatan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-display font-bold text-white">Jadwal Keberangkatan Travel</h2>
        <p class="text-xs text-slate-400">
            {{ auth()->check() && auth()->user()->isOwner() ? 'Atur tanggal keberangkatan, armada mobil, driver penanggung jawab, serta status perjalanan real-time.' : 'Daftar penugasan rute perjalanan Anda.' }}
        </p>
    </div>
    @if(auth()->check() && auth()->user()->isOwner())
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/20 transition-all">
        <span class="material-symbols-outlined text-sm">add</span>
        Tambah Jadwal
    </button>
    @else
    <div class="px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-amber-400 text-xs flex items-center gap-1.5 font-medium">
        <span class="material-symbols-outlined text-sm">route</span> Jadwal Perjalanan Tugas Driver
    </div>
    @endif
</div>

<!-- Schedules Table View -->
<div class="glass-panel rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800">
                    <th class="pb-3 font-semibold">Tgl & Jam Keberangkatan</th>
                    <th class="pb-3 font-semibold">Armada Mobil</th>
                    <th class="pb-3 font-semibold">Driver Penanggung Jawab</th>
                    <th class="pb-3 font-semibold">Rute Perjalanan</th>
                    <th class="pb-3 font-semibold">Booking Terhubung</th>
                    <th class="pb-3 font-semibold">Status Perjalanan</th>
                    <th class="pb-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($schedules as $schedule)
                <tr>
                    <td class="py-3.5 whitespace-nowrap">
                        <span class="font-bold text-white block">{{ $schedule->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                        <span class="text-[11px] text-sky-400 font-medium">{{ $schedule->tanggal_keberangkatan->translatedFormat('l') }}</span>
                    </td>
                    <td class="py-3.5">
                        @if($schedule->vehicle)
                            <span class="font-bold text-white block">{{ $schedule->vehicle->plat_nomor }}</span>
                            <span class="text-[11px] text-slate-400">{{ $schedule->vehicle->merk }} ({{ $schedule->vehicle->kapasitas }} Seat)</span>
                        @else
                            <span class="text-slate-500 italic">Armada Belum Set</span>
                        @endif
                    </td>
                    <td class="py-3.5 text-slate-200 font-medium">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-sky-400 text-[10px] font-bold border border-slate-700">
                                {{ strtoupper(substr($schedule->driver->nama ?? 'S', 0, 1)) }}
                            </span>
                            <span>{{ $schedule->driver->nama ?? 'Sopir Belum Set' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 text-slate-200">
                        <span class="font-semibold block">{{ $schedule->rute }}</span>
                    </td>
                    <td class="py-3.5">
                        <span class="px-2.5 py-1 rounded-md bg-slate-800 text-sky-400 font-bold border border-slate-700">
                            {{ $schedule->bookings_count ?? 0 }} Tiket
                        </span>
                    </td>
                    <td class="py-3.5 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $schedule->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($schedule->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : ($schedule->status_perjalanan === 'Dibatalkan' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20')) }}">
                            {{ $schedule->status_perjalanan }}
                        </span>
                    </td>
                    <td class="py-3.5 text-right whitespace-nowrap">
                        @if(auth()->check() && auth()->user()->isOwner())
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(@json($schedule))' class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                                </button>
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirmDelete(this, 'jadwal rute {{ $schedule->rute }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" class="inline-flex justify-end gap-1.5">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="vehicle_id" value="{{ $schedule->vehicle_id }}">
                                <input type="hidden" name="driver_id" value="{{ $schedule->driver_id }}">
                                <input type="hidden" name="rute" value="{{ $schedule->rute }}">
                                <input type="hidden" name="tanggal_keberangkatan" value="{{ $schedule->tanggal_keberangkatan->format('Y-m-d\TH:i') }}">

                                @if($schedule->status_perjalanan === 'Terjadwal')
                                    <input type="hidden" name="status_perjalanan" value="Dalam Perjalanan">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs flex items-center gap-1 shadow-lg shadow-amber-600/20">
                                        <span class="material-symbols-outlined text-sm">directions_car</span> Mulai Jalan
                                    </button>
                                @elseif($schedule->status_perjalanan === 'Dalam Perjalanan')
                                    <input type="hidden" name="status_perjalanan" value="Selesai">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs flex items-center gap-1 shadow-lg shadow-emerald-600/20">
                                        <span class="material-symbols-outlined text-sm">check_circle</span> Selesai Perjalanan
                                    </button>
                                @else
                                    <span class="text-emerald-400 text-xs font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">task_alt</span> Perjalanan Selesai
                                    </span>
                                @endif
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-500">Belum ada jadwal keberangkatan yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->check() && auth()->user()->isOwner())
<!-- Modal Tambah/Edit Jadwal (Hanya Owner) -->
<div id="scheduleModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tambah Jadwal Perjalanan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="scheduleForm" method="POST" action="{{ route('schedules.store') }}" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <!-- Quick Route Selector from Booking Travel -->
            <div class="bg-slate-900/80 p-3.5 rounded-xl border border-sky-500/30">
                <label class="block font-semibold text-sky-400 mb-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">sync_alt</span>
                    Ambil Rute dari Booking Travel (Otomatis Isi)
                </label>
                <select id="booking_picker" onchange="onBookingSelected(this)" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Rute dari Booking Terdaftar --</option>
                    @foreach($bookings as $b)
                        <option value="{{ $b->id }}" 
                                data-rute="{{ $b->asal }} - {{ $b->tujuan }}"
                                data-date="{{ $b->tanggal_berangkat->format('Y-m-d') }}"
                                data-driver-id="{{ $b->driver_id }}">
                            Rute: {{ $b->asal }} → {{ $b->tujuan }} (Tgl: {{ $b->tanggal_berangkat->format('d/m/Y') }} - Sopir: {{ $b->driver->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Tanggal & Jam Keberangkatan</label>
                <input type="datetime-local" id="tanggal_keberangkatan" name="tanggal_keberangkatan" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Rute Perjalanan</label>
                <input type="text" id="rute" name="rute" placeholder="Contoh: Jakarta - Bandung (Via Cipularang)" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1 flex items-center justify-between">
                    <span>Pilih Mobil Kendaraan (Tersedia)</span>
                    <span class="text-[10px] text-emerald-400 font-normal">✔ Memilih Armada SIAP / Tersedia</span>
                </label>
                <select id="vehicle_id" name="vehicle_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Mobil (Armada Ready) --</option>
                    @foreach($allVehicles as $v)
                        <option value="{{ $v->id }}" 
                                data-status="{{ $v->status }}"
                                class="{{ $v->status !== 'Tersedia' ? 'non-available-vehicle hidden' : '' }}">
                            {{ $v->plat_nomor }} - {{ $v->merk }} {{ $v->status === 'Tersedia' ? '[SIAP]' : '['.$v->status.']' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1 flex items-center justify-between">
                    <span>Pilih Driver (Sopir)</span>
                    <span class="text-[10px] text-emerald-400 font-normal">✔ Memilih Sopir SIAP / Aktif</span>
                </label>
                <select id="driver_id" name="driver_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Driver (Sopir Ready) --</option>
                    @foreach($allDrivers as $d)
                        <option value="{{ $d->id }}" data-status="{{ $d->status_aktif }}">
                            {{ $d->nama }} {{ $d->status_aktif === 'Aktif' ? '[SIAP]' : '['.$d->status_aktif.']' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-slate-300 mb-1">Status Perjalanan</label>
                <select id="status_perjalanan" name="status_perjalanan" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="Terjadwal">Terjadwal (Menunggu Keberangkatan)</option>
                    <option value="Dalam Perjalanan">Dalam Perjalanan (Sedang Jalan)</option>
                    <option value="Selesai">Selesai (Perjalanan Tiba di Tujuan)</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-500 shadow-lg shadow-sky-600/20">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('scripts')
@if(auth()->check() && auth()->user()->isOwner())
<script>
    function onBookingSelected(selectEl) {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const rute = selectedOption.dataset.rute;
            const dateStr = selectedOption.dataset.date;
            const driverId = selectedOption.dataset.driverId;

            if (rute) {
                document.getElementById('rute').value = rute;
            }

            if (dateStr) {
                document.getElementById('tanggal_keberangkatan').value = dateStr + 'T08:00';
            }

            const driverSelect = document.getElementById('driver_id');
            if (driverId && driverSelect && driverSelect.tagName === 'SELECT') {
                driverSelect.value = driverId;
            }
        }
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Jadwal Perjalanan';
        document.getElementById('scheduleForm').action = "{{ route('schedules.store') }}";
        document.getElementById('methodField').value = 'POST';

        document.querySelectorAll('.non-available-vehicle').forEach(opt => opt.classList.add('hidden'));

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('tanggal_keberangkatan').value = now.toISOString().slice(0, 16);
        
        document.getElementById('vehicle_id').value = '';
        
        const driverSelect = document.getElementById('driver_id');
        if (driverSelect && driverSelect.tagName === 'SELECT') {
            driverSelect.value = '';
        }
        
        document.getElementById('rute').value = '';
        document.getElementById('status_perjalanan').value = 'Terjadwal';
        document.getElementById('booking_picker').value = '';

        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function openEditModal(schedule) {
        document.getElementById('modalTitle').innerText = 'Edit Jadwal Perjalanan';
        document.getElementById('scheduleForm').action = "/schedules/" + schedule.id;
        document.getElementById('methodField').value = 'PUT';

        document.querySelectorAll('.non-available-vehicle').forEach(opt => {
            if (opt.value == schedule.vehicle_id) {
                opt.classList.remove('hidden');
            } else {
                opt.classList.add('hidden');
            }
        });

        if (schedule.tanggal_keberangkatan) {
            const dateStr = schedule.tanggal_keberangkatan.replace(' ', 'T').substring(0, 16);
            document.getElementById('tanggal_keberangkatan').value = dateStr;
        }

        document.getElementById('vehicle_id').value = schedule.vehicle_id;

        const driverSelect = document.getElementById('driver_id');
        if (driverSelect && driverSelect.tagName === 'SELECT') {
            driverSelect.value = schedule.driver_id;
        }

        document.getElementById('rute').value = schedule.rute;
        document.getElementById('status_perjalanan').value = schedule.status_perjalanan;

        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }
</script>
@endif
@endsection
