@extends('layouts.app')

@section('title', 'Booking Travel')
@section('page_title', 'Manajemen Booking Travel & Reservasi')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Data Reservasi & Booking Travel</h2>
        <p class="text-xs text-slate-400 mt-0.5">Kelola pemesanan travel, tentukan armada, supir, rute asal-tujuan, dan verifikasi status keberangkatan.</p>
    </div>
    <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all self-start md:self-auto">
        <span class="material-symbols-outlined text-base">add_circle</span>
        Tambah Booking Baru
    </button>
</div>

<!-- Search & Filter Controls -->
<div class="glass-panel rounded-2xl p-4 mt-6 border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="relative flex-1 max-w-md">
        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-500 text-sm">search</span>
        <input type="text" id="bookingSearchInput" onkeyup="filterBookingTable()" placeholder="Cari nama penumpang, asal, tujuan, atau plat nomor..." 
               class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-900/90 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-sky-500">
    </div>

    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-semibold">
        <button onclick="filterStatus('Semua')" class="filter-tab-btn active px-3 py-1.5 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-500/30 transition-all">Semua</button>
        <button onclick="filterStatus('Menunggu Verifikasi')" class="filter-tab-btn px-3 py-1.5 rounded-xl text-slate-400 hover:bg-slate-800 transition-all">Menunggu</button>
        <button onclick="filterStatus('Terverifikasi')" class="filter-tab-btn px-3 py-1.5 rounded-xl text-slate-400 hover:bg-slate-800 transition-all">Terverifikasi</button>
        <button onclick="filterStatus('Ditolak')" class="filter-tab-btn px-3 py-1.5 rounded-xl text-slate-400 hover:bg-slate-800 transition-all">Ditolak</button>
    </div>
</div>

<!-- Bookings Table View -->
<div class="glass-panel rounded-3xl p-6 mt-4 border border-slate-800">
    <div class="overflow-x-auto">
        <table id="bookingTable" class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800/80">
                    <th class="pb-3.5 font-semibold">Tgl Berangkat</th>
                    <th class="pb-3.5 font-semibold">Armada Mobil</th>
                    <th class="pb-3.5 font-semibold">Sopir (Driver)</th>
                    <th class="pb-3.5 font-semibold">Rute & Kursi</th>
                    <th class="pb-3.5 font-semibold">Tarif & Pembayaran</th>
                    <th class="pb-3.5 font-semibold">Status Verifikasi</th>
                    <th class="pb-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($bookings as $booking)
                @php
                    $scheduleStatus = $booking->schedule->status_perjalanan ?? 'Terjadwal';
                    $isVerified = ($booking->status_verifikasi ?? 'Terverifikasi') === 'Terverifikasi';
                    $isSupir = auth()->check() && auth()->user()->isSupir();
                    $isLockedForSupir = $isSupir && ($isVerified || in_array($scheduleStatus, ['Dalam Perjalanan', 'Selesai']));
                @endphp
                <tr class="booking-row hover:bg-slate-900/40 transition-colors" data-status="{{ $booking->status_verifikasi ?? 'Terverifikasi' }}">
                    <td class="py-4 whitespace-nowrap">
                        <span class="font-bold text-white block text-sm">{{ $booking->tanggal_berangkat->format('d M Y') }}</span>
                        <span class="text-[11px] text-slate-400">s/d {{ $booking->tanggal_selesai ? $booking->tanggal_selesai->format('d M Y') : '-' }} ({{ $booking->lama_hari }} Hari)</span>
                    </td>
                    <td class="py-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800">
                                {{ $booking->vehicle->plat_nomor }}
                            </span>
                            <div>
                                <span class="font-semibold text-white block text-xs">{{ $booking->vehicle->merk }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 text-slate-200">
                        <span class="font-bold block text-white text-xs">{{ $booking->driver->nama }}</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->driver->nomor_hp) }}" target="_blank" class="text-[11px] text-sky-400 hover:underline flex items-center gap-0.5 mt-0.5 font-mono">
                            <span class="material-symbols-outlined text-[12px]">call</span> {{ $booking->driver->nomor_hp }}
                        </a>
                    </td>
                    <td class="py-4">
                        <div class="flex items-center gap-1 text-sky-400 font-bold text-xs">
                            <span>{{ $booking->asal }}</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            <span>{{ $booking->tujuan }}</span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-0.5 block">{{ $booking->jumlah_kursi }} Seat dipesan (a.n {{ $booking->nama_penumpang }})</span>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <span class="font-mono font-bold text-emerald-400 block text-xs">Rp {{ number_format($booking->tarif, 0, ',', '.') }}</span>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ $booking->status_pembayaran === 'Lunas' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                {{ $booking->status_pembayaran }}
                            </span>
                            @if($booking->status_pembayaran === 'DP')
                                <span class="text-[10px] text-slate-400 font-mono">DP: Rp {{ number_format($booking->harga_dp, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase
                            {{ ($booking->status_verifikasi ?? 'Terverifikasi') === 'Terverifikasi' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : (($booking->status_verifikasi ?? '') === 'Menunggu Verifikasi' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            {{ $booking->status_verifikasi ?? 'Terverifikasi' }}
                        </span>
                    </td>
                    <td class="py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">

                            <!-- Owner Verification Action Button -->
                            @if(auth()->check() && auth()->user()->isOwner() && ($booking->status_verifikasi ?? '') === 'Menunggu Verifikasi')
                                <button onclick='openOwnerVerifyModal(@json($booking))' class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-lg shadow-amber-600/20 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">fact_check</span> Verifikasi Owner
                                </button>
                            @endif

                            @if($isLockedForSupir)
                                <!-- Badge Locked for Supir -->
                                <span class="px-2.5 py-1 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 text-[10px] font-semibold flex items-center gap-1" title="Booking telah disetujui/jalan, tidak dapat diubah oleh supir">
                                    <span class="material-symbols-outlined text-xs text-amber-400">lock</span> Disetujui
                                </span>
                            @else
                                <button onclick='openEditModal(@json($booking))' class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all flex items-center gap-1" title="Edit Booking">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirmDelete(this, 'booking travel rute {{ $booking->asal }} - {{ $booking->tujuan }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center gap-1" title="Hapus Booking">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-slate-500 font-medium">Belum ada transaksi booking travel tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Verifikasi Booking Khusus Owner -->
@if(auth()->check() && auth()->user()->isOwner())
<div id="ownerVerifyModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-6 shadow-2xl border border-amber-500/30">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Verifikasi Booking Travel</h3>
                <p id="ownerVerifySub" class="text-xs text-amber-400 font-medium mt-0.5"></p>
            </div>
            <button onclick="closeOwnerVerifyModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="ownerVerifyForm" method="POST" action="" class="mt-4 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Keputusan Verifikasi Owner</label>
                <select name="status_verifikasi" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500 font-semibold">
                    <option value="Terverifikasi">✔ Setujui / Terverifikasi (Terbitkan Jadwal Otomatis)</option>
                    <option value="Ditolak">✖ Tolak Booking Travel</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Catatan Owner (Opsional)</label>
                <textarea name="catatan_verifikasi" rows="3" placeholder="Contoh: Booking diverifikasi, jadwal otomatis terbit." class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeOwnerVerifyModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-500 shadow-lg shadow-emerald-600/20">Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Tambah/Edit Booking -->
<div id="bookingModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-2xl rounded-3xl p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tambah Booking Travel Baru</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">

            <!-- Selection Grid: Armada Mobil & Sopir Driver -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>Pilih Armada Mobil (Tersedia)</span>
                        <span class="text-[10px] text-emerald-400 font-normal">✔ Memilih Armada Ready</span>
                    </label>
                    <select id="vehicle_id" name="vehicle_id" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Mobil (Ready / Tersedia) --</option>
                        @foreach($allVehicles as $v)
                            @php
                                $isScheduled = in_array($v->id, $scheduledVehicleIds ?? []);
                            @endphp
                            <option value="{{ $v->id }}" 
                                    data-kapasitas="{{ $v->kapasitas }}" 
                                    data-status="{{ $v->status }}"
                                    class="{{ ($v->status !== 'Tersedia' || $isScheduled) ? 'non-available-vehicle hidden' : '' }}">
                                {{ $v->plat_nomor }} - {{ $v->merk }} ({{ $v->kapasitas }} Seat) {{ $isScheduled ? '[Terjadwal]' : ($v->status === 'Tersedia' ? '[SIAP]' : '['.$v->status.']') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->check() && auth()->user()->isSupir())
                    @php
                        $myDriverObj = $allDrivers->firstWhere('id', auth()->user()->driver_id) ?? $allDrivers->firstWhere('nama', auth()->user()->name) ?? $allDrivers->first();
                        $myDriverId = $myDriverObj->id ?? 1;
                        $myDriverName = $myDriverObj->nama ?? auth()->user()->name;
                    @endphp
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Sopir Penanggung Jawab</label>
                        <div class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-amber-400 font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-amber-400">badge</span>
                            <span>{{ $myDriverName }}</span>
                            <span class="text-[10px] text-slate-400 font-normal ml-auto">(Otomatis Driver Akun Ini)</span>
                        </div>
                        <input type="hidden" id="driver_id" name="driver_id" value="{{ $myDriverId }}">
                    </div>
                @else
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Pilih Sopir (Driver)</span>
                            <span class="text-[10px] text-emerald-400 font-normal">✔ Sopir Ready</span>
                        </label>
                        <select id="driver_id" name="driver_id" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            <option value="">-- Pilih Driver (Ready / Aktif) --</option>
                            @foreach($readyDrivers as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }} (HP: {{ $d->nomor_hp }}) - SIM: {{ $d->nomor_sim }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Route Details: Asal & Tujuan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Kota / Terminal Asal</label>
                    <input type="text" id="asal" name="asal" placeholder="Jakarta (Pulo Gebang)" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Kota / Destinasi Tujuan</label>
                    <input type="text" id="tujuan" name="tujuan" placeholder="Bandung (Dipatiukur)" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <!-- Date & Duration Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Tgl Berangkat</label>
                    <input type="date" id="tanggal_berangkat" name="tanggal_berangkat" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Lama Hari Sewa</label>
                    <input type="number" id="lama_hari" name="lama_hari" min="1" value="1" oninput="calculateTotalTarif()" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>Jumlah Kursi</span>
                        <span class="text-[10px] text-amber-400 font-bold" id="kursi_limit_hint">(Max --)</span>
                    </label>
                    <input type="number" id="jumlah_kursi" name="jumlah_kursi" min="1" placeholder="Misal: 7" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <!-- Pricing & Payment Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-800">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Total Tarif Travel (Rp)</label>
                    <input type="text" inputmode="numeric" id="tarif" name="tarif" placeholder="Otomatis terhitung" required class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Status Pembayaran</label>
                    <select id="status_pembayaran" name="status_pembayaran" onchange="toggleStatusPembayaranMode()" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                        <option value="Lunas">Lunas (100% Pembayaran)</option>
                        <option value="DP">DP (Uang Muka)</option>
                    </select>
                </div>

                <div id="dp_field_container">
                    <label class="block font-semibold text-slate-300 mb-1.5">Jumlah DP Diterima (Rp)</label>
                    <input type="text" inputmode="numeric" id="harga_dp" name="harga_dp" placeholder="Nominal DP" class="currency-input w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-amber-400 font-mono font-bold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold hover:from-sky-500 hover:to-indigo-500 shadow-lg shadow-sky-600/30">Simpan Booking</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let selectedVehicleRate = 0;
    let selectedVehicleCapacity = 0;

    function filterBookingTable() {
        const query = document.getElementById('bookingSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.booking-row');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    function filterStatus(status) {
        document.querySelectorAll('.filter-tab-btn').forEach(btn => {
            if (btn.innerText.includes(status) || (status === 'Semua' && btn.innerText === 'Semua')) {
                btn.className = 'filter-tab-btn active px-3 py-1.5 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-500/30 transition-all font-semibold';
            } else {
                btn.className = 'filter-tab-btn px-3 py-1.5 rounded-xl text-slate-400 hover:bg-slate-800 transition-all font-semibold';
            }
        });

        const rows = document.querySelectorAll('.booking-row');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'Semua' || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function onVehicleSelected(selectElem) {
        const selectedOpt = selectElem.options[selectElem.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            selectedVehicleRate = parseFloat(selectedOpt.getAttribute('data-tarif')) || 0;
            selectedVehicleCapacity = parseInt(selectedOpt.getAttribute('data-kapasitas')) || 0;

            document.getElementById('tarif_per_hari_hint').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(selectedVehicleRate) + "/Hari";
            document.getElementById('kursi_limit_hint').innerText = "(Max " + selectedVehicleCapacity + " Seat)";
            
            const jumlahKursiInput = document.getElementById('jumlah_kursi');
            jumlahKursiInput.max = selectedVehicleCapacity;
            if (parseInt(jumlahKursiInput.value) > selectedVehicleCapacity) {
                jumlahKursiInput.value = selectedVehicleCapacity;
            }

            calculateTotalTarif();
        } else {
            selectedVehicleRate = 0;
            selectedVehicleCapacity = 0;
            document.getElementById('tarif_per_hari_hint').innerText = "--";
            document.getElementById('kursi_limit_hint').innerText = "(Max --)";
        }
    }

    function calculateTotalTarif() {
        const lamaHari = parseInt(document.getElementById('lama_hari').value) || 1;
        const total = selectedVehicleRate * lamaHari;
        if (total > 0) {
            setCurrencyInputValue(document.getElementById('tarif'), total);
            if (document.getElementById('status_pembayaran').value === 'Lunas') {
                setCurrencyInputValue(document.getElementById('harga_dp'), total);
            }
        }
    }

    function toggleStatusPembayaranMode() {
        const status = document.getElementById('status_pembayaran').value;
        const dpInput = document.getElementById('harga_dp');
        const tarifVal = getCurrencyInputValue(document.getElementById('tarif'));

        if (status === 'Lunas') {
            setCurrencyInputValue(dpInput, tarifVal);
            dpInput.readOnly = true;
            dpInput.classList.add('opacity-60');
        } else {
            dpInput.readOnly = false;
            dpInput.classList.remove('opacity-60');
            const dpVal = getCurrencyInputValue(dpInput);
            if (dpVal === tarifVal && tarifVal > 0) {
                setCurrencyInputValue(dpInput, Math.round(tarifVal * 0.5));
            }
        }
    }

    @if(auth()->check() && auth()->user()->isOwner())
    function openOwnerVerifyModal(booking) {
        document.getElementById('ownerVerifySub').innerText = "Rute: " + booking.asal + " ➔ " + booking.tujuan + " (Mobil: " + (booking.vehicle ? booking.vehicle.plat_nomor : '') + ")";
        document.getElementById('ownerVerifyForm').action = "/bookings/" + booking.id + "/verify";
        document.getElementById('ownerVerifyModal').classList.remove('hidden');
    }

    function closeOwnerVerifyModal() {
        document.getElementById('ownerVerifyModal').classList.add('hidden');
    }
    @endif

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Booking Travel Baru';
        document.getElementById('bookingForm').action = "{{ route('bookings.store') }}";
        document.getElementById('methodField').value = 'POST';

        document.querySelectorAll('.non-available-vehicle').forEach(opt => opt.classList.add('hidden'));

        document.getElementById('vehicle_id').value = '';
        
        const driverSelect = document.getElementById('driver_id');
        if (driverSelect && driverSelect.tagName === 'SELECT') {
            driverSelect.value = '';
        }
        
        document.getElementById('asal').value = '';
        document.getElementById('tujuan').value = '';
        document.getElementById('tanggal_berangkat').value = "{{ date('Y-m-d') }}";
        document.getElementById('lama_hari').value = '1';
        document.getElementById('jumlah_kursi').value = '';
        setCurrencyInputValue(document.getElementById('tarif'), '');
        setCurrencyInputValue(document.getElementById('harga_dp'), '');
        document.getElementById('status_pembayaran').value = 'Lunas';

        selectedVehicleRate = 0;
        document.getElementById('tarif_per_hari_hint').innerText = "--";
        document.getElementById('kursi_limit_hint').innerText = "(Max --)";
        
        toggleStatusPembayaranMode();
        document.getElementById('bookingModal').classList.remove('hidden');
    }

    function openEditModal(booking) {
        document.getElementById('modalTitle').innerText = 'Edit Booking Travel';
        document.getElementById('bookingForm').action = "/bookings/" + booking.id;
        document.getElementById('methodField').value = 'PUT';

        document.querySelectorAll('.non-available-vehicle').forEach(opt => {
            if (opt.value == booking.vehicle_id) {
                opt.classList.remove('hidden');
            } else {
                opt.classList.add('hidden');
            }
        });

        const vehicleSelect = document.getElementById('vehicle_id');
        vehicleSelect.value = booking.vehicle_id;
        onVehicleSelected(vehicleSelect);

        const driverSelect = document.getElementById('driver_id');
        if (driverSelect && driverSelect.tagName === 'SELECT') {
            driverSelect.value = booking.driver_id;
        }

        document.getElementById('asal').value = booking.asal;
        document.getElementById('tujuan').value = booking.tujuan;
        document.getElementById('tanggal_berangkat').value = booking.tanggal_berangkat.substring(0, 10);
        document.getElementById('lama_hari').value = booking.lama_hari || 1;
        document.getElementById('jumlah_kursi').value = booking.jumlah_kursi;
        setCurrencyInputValue(document.getElementById('tarif'), booking.tarif);
        document.getElementById('status_pembayaran').value = booking.status_pembayaran;
        setCurrencyInputValue(document.getElementById('harga_dp'), booking.harga_dp);

        calculateTotalTarif();
        toggleStatusPembayaranMode();
        document.getElementById('bookingModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }
</script>
@endsection
