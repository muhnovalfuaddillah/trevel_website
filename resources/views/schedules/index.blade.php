@extends('layouts.app')

@section('title', 'Jadwal Perjalanan')
@section('page_title', 'Jadwal Perjalanan & Keberangkatan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Jadwal Keberangkatan Travel</h2>
        <p class="text-xs text-slate-400 mt-0.5">
            {{ auth()->check() && auth()->user()->isOwner() ? 'Atur tanggal keberangkatan, armada mobil, driver penanggung jawab, serta status perjalanan real-time.' : 'Daftar penugasan rute perjalanan Anda.' }}
        </p>
    </div>
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all self-start md:self-auto">
        <span class="material-symbols-outlined text-base">add_circle</span>
        Tambah Jadwal Baru
    </button>
</div>

<!-- Schedules Table View -->
<div class="glass-panel rounded-3xl p-4 sm:p-6 mt-6 border border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs min-w-[700px]">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800/80">
                    <th class="pb-3.5 font-semibold">Tgl & Jam Keberangkatan</th>
                    <th class="pb-3.5 font-semibold">Armada Mobil</th>
                    <th class="pb-3.5 font-semibold">Driver Penanggung Jawab</th>
                    <th class="pb-3.5 font-semibold">Rute Perjalanan</th>
                    <th class="pb-3.5 font-semibold text-emerald-400">Pemasukan / Tarif (Rp)</th>
                    <th class="pb-3.5 font-semibold">Status Perjalanan</th>
                    <th class="pb-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <span class="font-bold text-white block text-sm">{{ $schedule->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                        <span class="text-[11px] text-sky-400 font-semibold">{{ $schedule->tanggal_keberangkatan->translatedFormat('l') }}</span>
                    </td>
                    <td class="py-4">
                        @if($schedule->vehicle)
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800">
                                    {{ $schedule->vehicle->plat_nomor }}
                                </span>
                                <div>
                                    <span class="font-semibold text-white block text-xs">{{ $schedule->vehicle->merk }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $schedule->vehicle->kapasitas }} Seat</span>
                                </div>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Armada Belum Set</span>
                        @endif
                    </td>
                    <td class="py-4 text-slate-200">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white text-[10px] font-bold shadow-sm shrink-0">
                                    {{ strtoupper(substr($schedule->driver->nama ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-white block text-xs">{{ $schedule->driver->nama ?? 'Sopir Belum Set' }}</span>
                                    <span class="text-[9px] text-amber-400 font-bold uppercase tracking-wider">Driver Utama</span>
                                </div>
                            </div>
                            @if($schedule->driver2)
                            <div class="flex items-center gap-2 pt-1 border-t border-slate-800/80">
                                <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-[10px] font-bold shadow-sm shrink-0">
                                    {{ strtoupper(substr($schedule->driver2->nama ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-sky-200 block text-xs">{{ $schedule->driver2->nama }}</span>
                                    <span class="text-[9px] text-sky-400 font-bold uppercase tracking-wider">Driver 2 (Pendamping)</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 text-slate-200">
                        <span class="font-semibold text-white text-xs block">{{ $schedule->rute }}</span>
                    </td>
                    <td class="py-4 font-mono font-bold text-emerald-400 text-sm whitespace-nowrap">
                        Rp {{ number_format($schedule->tarif ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase
                            {{ $schedule->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($schedule->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : ($schedule->status_perjalanan === 'Dibatalkan' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20')) }}">
                            {{ $schedule->status_perjalanan }}
                        </span>
                    </td>
                    <td class="py-4 text-right whitespace-nowrap">
                        @if(auth()->check() && auth()->user()->isOwner())
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(@json($schedule))' class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all flex items-center gap-1" title="Edit Jadwal">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirmDelete(this, 'jadwal rute {{ $schedule->rute }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center gap-1" title="Hapus Jadwal">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" class="inline-flex justify-end gap-1.5">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="vehicle_id" value="{{ $schedule->vehicle_id }}">
                                <input type="hidden" name="driver_id" value="{{ $schedule->driver_id }}">
                                <input type="hidden" name="driver_2_id" value="{{ $schedule->driver_2_id }}">
                                <input type="hidden" name="rute" value="{{ $schedule->rute }}">
                                <input type="hidden" name="tanggal_keberangkatan" value="{{ $schedule->tanggal_keberangkatan->format('Y-m-d\TH:i') }}">

                                @if($schedule->status_perjalanan === 'Terjadwal')
                                    <input type="hidden" name="status_perjalanan" value="Dalam Perjalanan">
                                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs flex items-center gap-1 shadow-lg shadow-amber-600/20 transition-all">
                                        <span class="material-symbols-outlined text-base">directions_car</span> Mulai Jalan
                                    </button>
                                @elseif($schedule->status_perjalanan === 'Dalam Perjalanan')
                                    <input type="hidden" name="status_perjalanan" value="Selesai">
                                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1 shadow-lg shadow-emerald-600/20 transition-all">
                                        <span class="material-symbols-outlined text-base">check_circle</span> Selesai Perjalanan
                                    </button>
                                @else
                                    <span class="text-emerald-400 text-xs font-bold flex items-center gap-1 bg-emerald-500/10 px-3 py-1.5 rounded-xl border border-emerald-500/20">
                                        <span class="material-symbols-outlined text-base">task_alt</span> Perjalanan Selesai
                                    </span>
                                @endif
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-slate-500 font-medium">Belum ada jadwal keberangkatan yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Jadwal -->
<div id="scheduleModal" onclick="if(event.target === this) closeModal()" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
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
            <div class="bg-slate-900/90 p-3.5 rounded-2xl border border-sky-500/30 space-y-1.5">
                <label class="block font-bold text-sky-400 flex items-center gap-1">
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
                <label class="block font-semibold text-slate-300 mb-1.5">Tanggal & Jam Keberangkatan</label>
                <input type="datetime-local" id="tanggal_keberangkatan" name="tanggal_keberangkatan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Rute Perjalanan</label>
                <input type="text" id="rute" name="rute" placeholder="Contoh: Jakarta - Bandung (Via Cipularang)" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                    <span>Pilih Mobil Kendaraan (Tersedia)</span>
                    <span class="text-[10px] text-emerald-400 font-normal">✔ Memilih Armada Ready</span>
                </label>
                <select id="vehicle_id" name="vehicle_id" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Mobil (Armada Ready) --</option>
                    @foreach($allVehicles as $v)
                        @php
                            $isVehBusy = in_array($v->id, $busyVehicleIds ?? []) || $v->status !== 'Tersedia';
                        @endphp
                        <option value="{{ $v->id }}" 
                                data-status="{{ $v->status }}"
                                class="{{ $isVehBusy ? 'non-available-vehicle hidden' : '' }}">
                            {{ $v->plat_nomor }} - {{ $v->merk }} {{ $isVehBusy ? '('.($v->status !== 'Tersedia' ? $v->status : 'Ada Jadwal').')' : '[SIAP]' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @if(auth()->check() && auth()->user()->isSupir())
                    @php
                        $myDriverRec = $allDrivers->firstWhere('id', auth()->user()->driver_id) ?? $allDrivers->firstWhere('nama', auth()->user()->name);
                        $myDriverId = $myDriverRec->id ?? 0;
                        $myDriverNama = $myDriverRec->nama ?? auth()->user()->name;
                    @endphp
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Driver 1 / Utama (Wajib)</span>
                            <span class="text-[10px] text-amber-400 font-normal">✔ Otomatis Diri Anda</span>
                        </label>
                        <div class="px-3.5 py-2.5 rounded-2xl bg-slate-950 border border-amber-500/40 text-amber-300 font-bold flex items-center gap-2 text-xs">
                            <span class="material-symbols-outlined text-sm text-amber-400">person</span>
                            <span>{{ $myDriverNama }} (Diri Anda)</span>
                        </div>
                        <input type="hidden" id="driver_id" name="driver_id" value="{{ $myDriverId }}">
                    </div>
                @else
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Driver 1 / Utama (Wajib)</span>
                            <span class="text-[10px] text-emerald-400 font-normal">✔ Sopir Ready</span>
                        </label>
                        <select id="driver_id" name="driver_id" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            <option value="">-- Pilih Driver Utama --</option>
                            @foreach($allDrivers as $d)
                                @php
                                    $isDrvBusy = in_array($d->id, $busyDriverIds ?? []) || $d->status_aktif !== 'Aktif';
                                @endphp
                                <option value="{{ $d->id }}" 
                                        data-status="{{ $d->status_aktif }}"
                                        class="{{ $isDrvBusy ? 'non-available-driver hidden' : '' }}">
                                    {{ $d->nama }} {{ $isDrvBusy ? '('.($d->status_aktif !== 'Aktif' ? $d->status_aktif : 'Ada Jadwal').')' : '[SIAP]' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>Driver 2 / Pendamping (Opsional)</span>
                        <span class="text-[10px] text-sky-400 font-normal">Sopir Cadangan</span>
                    </label>
                    <select id="driver_2_id" name="driver_2_id" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-sky-400 font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Tanpa Driver 2 --</option>
                        @foreach($allDrivers as $d)
                            @php
                                $isSelf = auth()->check() && auth()->user()->isSupir() && (
                                    $d->id == (auth()->user()->driver_id ?? 0) || strtolower($d->nama) === strtolower(auth()->user()->name)
                                );
                                $isDrvBusy = in_array($d->id, $busyDriverIds ?? []) || $d->status_aktif !== 'Aktif';
                            @endphp
                            @if(!$isSelf)
                            <option value="{{ $d->id }}"
                                    class="{{ $isDrvBusy ? 'non-available-driver hidden' : '' }}">
                                {{ $d->nama }} {{ $isDrvBusy ? '('.($d->status_aktif !== 'Aktif' ? $d->status_aktif : 'Ada Jadwal').')' : '[SIAP]' }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Pemasukan / Tarif Perjalanan (Rp)</label>
                <input type="text" inputmode="numeric" id="tarif" name="tarif" placeholder="Contoh: 1.500.000" class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Status Perjalanan</label>
                <select id="status_perjalanan" name="status_perjalanan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="Terjadwal">Terjadwal (Menunggu Keberangkatan)</option>
                    <option value="Dalam Perjalanan">Dalam Perjalanan (Sedang Jalan)</option>
                    <option value="Selesai">Selesai (Perjalanan Tiba di Tujuan)</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold hover:from-sky-500 hover:to-indigo-500 shadow-lg shadow-sky-600/30">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
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
        document.querySelectorAll('.non-available-driver').forEach(opt => opt.classList.add('hidden'));

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('tanggal_keberangkatan').value = now.toISOString().slice(0, 16);
        
        document.getElementById('vehicle_id').value = '';
        
        const driverSelect = document.getElementById('driver_id');
        if (driverSelect && driverSelect.tagName === 'SELECT') {
            driverSelect.value = '';
            @if(auth()->check() && auth()->user()->isSupir())
                const myDriverId = "{{ auth()->user()->driver_id ?? ($allDrivers->where('nama', auth()->user()->name)->first()->id ?? '') }}";
                if (myDriverId) {
                    const myOpt = driverSelect.querySelector(`option[value="${myDriverId}"]`);
                    if (myOpt) myOpt.classList.remove('hidden');
                    driverSelect.value = myDriverId;
                }
            @endif
        }
        const driver2Select = document.getElementById('driver_2_id');
        if (driver2Select) {
            driver2Select.value = '';
        }
        
        document.getElementById('rute').value = '';
        setCurrencyInputValue(document.getElementById('tarif'), '');
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

        document.querySelectorAll('.non-available-driver').forEach(opt => {
            if (opt.value == schedule.driver_id || opt.value == schedule.driver_2_id) {
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

        const driver2Select = document.getElementById('driver_2_id');
        if (driver2Select) {
            driver2Select.value = schedule.driver_2_id || '';
        }

        document.getElementById('rute').value = schedule.rute;
        setCurrencyInputValue(document.getElementById('tarif'), schedule.tarif);
        document.getElementById('status_perjalanan').value = schedule.status_perjalanan;
        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }
</script>
@endsection
