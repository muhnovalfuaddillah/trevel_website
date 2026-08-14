<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100 dark scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TravelManager - Layanan Travel Shuttle & Sewa Mobil Eksekutif</title>
    <meta name="description" content="Sistem pemesanan tiket travel dan sewa armada mobil eksekutif antar kota dengan driver terverifikasi dan kenyamanan ekstra." />

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- AOS (Animate On Scroll) CSS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #020617;
            background-image: 
                radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.6) 0px, transparent 100%);
            background-attachment: fixed;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.85) 100%);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(2, 132, 199, 0.3);
        }

        .gradient-text-sky {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-text-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-200 bg-slate-950 selection:bg-sky-500 selection:text-white">

    <!-- Include Navbar Component -->
    @include('website.navbar')

    <!-- ==================== HERO SECTION ==================== -->
    <section id="beranda" class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden min-h-[85vh] flex items-center">
        <!-- Background High-Res Photography Gunung Bromo Probolinggo -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="w-full h-full bg-cover bg-center opacity-45 scale-105 transition-transform duration-1000 filter brightness-95 contrast-105"
                 style="background-image: url('https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=2070&auto=format&fit=crop');">
            </div>
            <!-- Dark Gradient Mesh Overlay for Maximum Contrast & Text Legibility -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-slate-950/75 to-slate-950"></div>
        </div>

        <!-- Floating Ambient Blur Accents -->
        <div class="absolute top-1/4 left-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                
                <!-- Badge Pill -->
                <div data-aos="fade-down" data-aos-duration="600" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-bold uppercase tracking-wider shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                    Spesialis Travel & Sewa Mobil Per Hari - Dari Probolinggo Outbound
                </div>

                <!-- Hero Heading -->
                <h1 data-aos="fade-up" data-aos-delay="100" class="text-3xl md:text-5xl lg:text-6xl font-display font-black text-white leading-tight tracking-wide">
                    Sewa Mobil Per Hari & Travel <span class="gradient-text-sky">Dari Probolinggo</span> Ke Luar Kota
                </h1>

                <!-- Hero Description -->
                <p data-aos="fade-up" data-aos-delay="200" class="text-slate-300 text-sm md:text-base font-normal leading-relaxed">
                    <strong>TravelManager</strong> menyediakan layanan sewa armada mobil per hari dan perjalanan travel eksekutif dengan penjemputan dari <strong>Probolinggo & Sekitarnya</strong> menuju berbagai kota (Surabaya, Juanda, Malang, Jember, Bali, dll). Antar-jemput alamat (door-to-door) dengan driver berpengalaman.
                </p>

                <!-- Action Buttons -->
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="#rute" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs shadow-xl shadow-sky-600/30 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">search</span>
                        Cari Sewa Harian Dari Probolinggo
                    </a>
                    <a href="#armada" class="px-6 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-semibold text-xs border border-slate-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-amber-400">directions_car</span>
                        Lihat Armada Sewa Harian
                    </a>
                </div>

            </div>

            <!-- Quick Booking Route Picker Form Card -->
            <div data-aos="zoom-in" data-aos-delay="400" class="glass-panel p-6 md:p-8 rounded-3xl mt-12 border border-slate-800 shadow-2xl max-w-4xl mx-auto">
                <form action="https://wa.me/{{ $cleanOwnerPhone ?? '6289629615301' }}" target="_blank" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs font-semibold">
                    <div>
                        <label class="block text-slate-300 mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sky-400 text-base">location_on</span> Penjemputan / Asal
                        </label>
                        <select id="hero_asal" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-sky-400 font-bold focus:outline-none focus:border-sky-500">
                            <option value="Probolinggo">PROBOLINGGO (Kota / Kraksaan / Paiton / Bromo)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-amber-400 text-base">flag</span> Kota Tujuan Anda
                        </label>
                        <select id="hero_tujuan" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                            <option value="Surabaya">Surabaya (Kota / Stasiun / Bandara Juanda)</option>
                            <option value="Malang">Malang (Kota / Batu)</option>
                            <option value="Pasuruan">Pasuruan / Sidoarjo</option>
                            <option value="Jember">Jember / Banyuwangi</option>
                            <option value="Bali / Kota Lain">Bali / Kota Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-emerald-400 text-base">event</span> Durasi / Tgl Sewa
                        </label>
                        <input type="date" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="flex items-end">
                        <button type="button" onclick="bookingViaWhatsApp()" class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/30 transition-all">
                            <span class="material-symbols-outlined text-base">chat</span>
                            Tanya Sewa Per Hari
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- ==================== RUTE POPULER SECTION ==================== -->
    <section id="rute" class="py-16 md:py-24 bg-slate-950/60 border-t border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div data-aos="fade-up" class="text-center max-w-2xl mx-auto space-y-3">
                <h2 class="text-2xl md:text-4xl font-display font-black text-white">Layanan Sewa Mobil Per Hari - Dari Probolinggo</h2>
                <p class="text-xs md:text-sm text-slate-400">Pilihan paket sewa mobil harian (dengan supir & BBM) penjemputan dari Probolinggo menuju kota tujuan Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Card Rute 1 -->
                <div data-aos="fade-up" data-aos-delay="100" class="glass-card p-6 rounded-3xl space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-sky-500/10 text-sky-400 border border-sky-500/20 text-[10px] font-bold uppercase tracking-wider">Sewa Harian / Shuttle</span>
                        <span class="font-mono font-bold text-emerald-400 text-sm">Sewa Harian + Driver</span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 text-white font-display font-bold text-lg">
                            <span>Probolinggo</span>
                            <span class="material-symbols-outlined text-sky-400 text-sm">arrow_forward</span>
                            <span>Surabaya / Juanda</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Penjemputan Probolinggo ➔ Surabaya & Bandara Juanda</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-300">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-amber-400">directions_car</span> HiAce / Innova</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-sky-400">schedule</span> Sistem Harian</span>
                    </div>
                </div>

                <!-- Card Rute 2 -->
                <div data-aos="fade-up" data-aos-delay="200" class="glass-card p-6 rounded-3xl space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] font-bold uppercase tracking-wider">Paket Harian All In</span>
                        <span class="font-mono font-bold text-emerald-400 text-sm">Sewa Harian + Driver</span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 text-white font-display font-bold text-lg">
                            <span>Probolinggo</span>
                            <span class="material-symbols-outlined text-amber-400 text-sm">arrow_forward</span>
                            <span>Malang / Batu</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Perjalanan Dinas, Wisata, & Acara Keluarga</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-300">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-amber-400">directions_car</span> Innova Zenix / Elf</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-sky-400">schedule</span> Sistem Harian</span>
                    </div>
                </div>

                <!-- Card Rute 3 -->
                <div data-aos="fade-up" data-aos-delay="300" class="glass-card p-6 rounded-3xl space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-wider">Drop Off / Charter</span>
                        <span class="font-mono font-bold text-emerald-400 text-sm">Tarif Terjangkau</span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 text-white font-display font-bold text-lg">
                            <span>Probolinggo</span>
                            <span class="material-symbols-outlined text-emerald-400 text-sm">arrow_forward</span>
                            <span>Kota Lainnya</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Jember, Pasuruan, Kediri, Bali, dll</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-300">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-amber-400">directions_car</span> Pilih Armada</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-sky-400">schedule</span> Sistem Harian</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== ARMADA MOBIL SECTION ==================== -->
    <section id="armada" class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div data-aos="fade-up" class="text-center max-w-2xl mx-auto space-y-3">
                <h2 class="text-2xl md:text-4xl font-display font-black text-white">Armada Kendaraan Travel Modern</h2>
                <p class="text-xs md:text-sm text-slate-400">Seluruh unit dirawat secara berkala dan berada dalam kondisi optimal untuk perjalanan jauh.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                @forelse($vehicles as $v)
                <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20 shadow-md">
                        <span class="material-symbols-outlined text-2xl">directions_car</span>
                    </div>

                    <div>
                        <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800">
                            {{ $v->plat_nomor }}
                        </span>
                        <h3 class="font-display font-bold text-lg text-white mt-2">{{ $v->merk }}</h3>
                        <p class="text-xs text-slate-400">{{ $v->kapasitas }} Kursi (Seat) Passenger</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Status Armada:</span>
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase tracking-wider
                            {{ $v->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($v->status === 'Beroperasi' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm shadow-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            @if($v->status === 'Beroperasi')
                                ⚡ BEROPERASI
                            @elseif($v->status === 'Tersedia')
                                ✔ TERSEDIA
                            @else
                                🛠 {{ strtoupper($v->status) }}
                            @endif
                        </span>
                    </div>

                    <div class="pt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tarif / Hari:</span>
                        <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($v->tarif_per_hari, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <!-- Fallback Vehicle Showcase -->
                <div data-aos="fade-up" data-aos-delay="100" class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <span class="material-symbols-outlined text-2xl">directions_car</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800">B 7123 SAA</span>
                        <h3 class="font-display font-bold text-lg text-white mt-2">Toyota HiAce Commuter 2.5</h3>
                        <p class="text-xs text-slate-400">14 Kursi Seat (Reclining & Full AC)</p>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tarif Sewa:</span>
                        <span class="font-mono font-bold text-emerald-400">Rp 1.200.000 / Hari</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="200" class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                        <span class="material-symbols-outlined text-2xl">airport_shuttle</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-amber-400 font-mono font-bold text-xs border border-slate-800">B 8890 WAA</span>
                        <h3 class="font-display font-bold text-lg text-white mt-2">Toyota HiAce Premio Luxury</h3>
                        <p class="text-xs text-slate-400">10 Captain Seats (VIP Comfort)</p>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tarif Sewa:</span>
                        <span class="font-mono font-bold text-emerald-400">Rp 1.500.000 / Hari</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="300" class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                        <span class="material-symbols-outlined text-2xl">directions_car</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-indigo-400 font-mono font-bold text-xs border border-slate-800">D 1452 TAA</span>
                        <h3 class="font-display font-bold text-lg text-white mt-2">Toyota Innova Zenix Hybrid</h3>
                        <p class="text-xs text-slate-400">7 Kursi Seat (Privat Family Charter)</p>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tarif Sewa:</span>
                        <span class="font-mono font-bold text-emerald-400">Rp 900.000 / Hari</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="400" class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                        <span class="material-symbols-outlined text-2xl">directions_bus</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 rounded-lg bg-slate-900 text-emerald-400 font-mono font-bold text-xs border border-slate-800">B 9912 KAA</span>
                        <h3 class="font-display font-bold text-lg text-white mt-2">Isuzu Elf Long Deluxe</h3>
                        <p class="text-xs text-slate-400">19 Kursi Seat (Group Tour & Shuttle)</p>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tarif Sewa:</span>
                        <span class="font-mono font-bold text-emerald-400">Rp 1.400.000 / Hari</span>
                    </div>
                </div>
                @endforelse

            </div>

            @if(isset($schedules) && count($schedules) > 0)
            <!-- Status Perjalanan & Jadwal Real-Time Database -->
            <div data-aos="fade-up" class="pt-10 border-t border-slate-800 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-display font-bold text-xl text-white">Status Perjalanan Real-Time</h3>
                        <p class="text-xs text-slate-400">Jadwal armada yang sedang berjalan atau dijadwalkan langsung dari database.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-sky-500/10 text-sky-400 border border-sky-500/20 text-xs font-bold">
                        ✔ Live Sync Database
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($schedules as $s)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" class="p-5 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-3 shadow-lg">
                        <div class="flex items-center justify-between">
                            <span class="font-display font-bold text-white text-sm">{{ $s->rute }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $s->status === 'Diproses' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($s->status === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                {{ $s->status }}
                            </span>
                        </div>

                        <div class="space-y-1 text-xs text-slate-400">
                            <div class="flex items-center justify-between">
                                <span>Tgl Berangkat:</span>
                                <strong class="text-slate-200">{{ $s->tanggal_keberangkatan->format('d M Y') }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Unit Mobil:</span>
                                <span class="px-2 py-0.5 rounded bg-slate-950 text-sky-400 font-mono font-bold text-[11px] border border-slate-800">
                                    {{ $s->vehicle->plat_nomor ?? '-' }} ({{ $s->vehicle->merk ?? '-' }})
                                </span>
                            </div>
                            @if($s->driver)
                            <div class="flex items-center justify-between pt-1 border-t border-slate-800/60">
                                <span>Pengemudi/Driver:</span>
                                <strong class="text-emerald-400">{{ $s->driver->nama }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>

    <!-- ==================== KEUNGGULAN SECTION ==================== -->
    <section id="keunggulan" class="py-16 md:py-24 bg-slate-950/80 border-t border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div data-aos="fade-up" class="text-center max-w-2xl mx-auto space-y-3">
                <h2 class="text-2xl md:text-4xl font-display font-black text-white">Mengapa Memilih TravelManager?</h2>
                <p class="text-xs md:text-sm text-slate-400">Jaminan kenyamanan ekstra dan kepastian jadwal perjalanan bagi Anda dan keluarga.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div data-aos="fade-up" data-aos-delay="100" class="glass-card p-6 rounded-3xl space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <span class="material-symbols-outlined text-2xl">badge</span>
                    </div>
                    <h3 class="font-display font-bold text-base text-white">Driver Terverifikasi SIM & KTP</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Seluruh pengemudi kami telah melalui verifikasi dokumen legalitas KTP & SIM resmi oleh manajemen travel.</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="200" class="glass-card p-6 rounded-3xl space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                        <span class="material-symbols-outlined text-2xl">chat</span>
                    </div>
                    <h3 class="font-display font-bold text-base text-white">Notifikasi WA Real-Time Fonnte</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Setiap bukti verifikasi booking dan jadwal keberangkatan akan dikirimkan otomatis ke WhatsApp Anda.</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="300" class="glass-card p-6 rounded-3xl space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                        <span class="material-symbols-outlined text-2xl">build</span>
                    </div>
                    <h3 class="font-display font-bold text-base text-white">Armada Mobil Rutin Servis</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Setiap unit kendaraan diperiksa kondisi oli, rem, dan AC secara berkala di bengkel resmi untuk keamanan maksimal.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== FORM ORDER SEWA / TRAVEL SECTION ==================== -->
    <section id="form-order" class="py-16 md:py-24 bg-slate-950/90 border-t border-slate-800 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div data-aos="zoom-in" class="glass-panel p-6 sm:p-8 md:p-10 rounded-3xl border border-emerald-500/30 max-w-4xl mx-auto shadow-2xl space-y-6 relative overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Form Pemesanan Instan
                        </div>
                        <h2 class="text-2xl md:text-3xl font-display font-black text-white">Form Order Travel & Sewa Mobil</h2>
                        <p class="text-xs text-slate-400 mt-1">Lengkapi rincian order di bawah ini untuk langsung terhubung & pesan via WhatsApp Owner.</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-3 self-start md:self-auto">
                        <span class="material-symbols-outlined text-3xl">chat</span>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-semibold">Terkirim Langsung Ke:</span>
                            <span class="font-mono font-bold text-xs">WA Owner ({{ $ownerPhone ?? '089629615301' }})</span>
                        </div>
                    </div>
                </div>

                <form id="directOrderForm" onsubmit="submitFormOrderToWA(event)" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-semibold">
                    
                    <div>
                        <label class="block text-slate-300 mb-1.5">Nama Lengkap Pemesan <span class="text-rose-400">*</span></label>
                        <input type="text" id="ord_nama" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5">Nomor WhatsApp Pemesan <span class="text-rose-400">*</span></label>
                        <input type="text" id="ord_wa" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5">Jenis Layanan <span class="text-rose-400">*</span></label>
                        <select id="ord_layanan" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500 font-semibold">
                            <option value="Sewa Mobil Per Hari ( include Driver )">Sewa Mobil Per Hari ( include Driver )</option>
                            <option value="Travel Shuttle / Drop Off">Travel Shuttle / Drop Off Perjalanan</option>
                            <option value="Charter Wisata Bromo & Probolinggo">Charter Wisata Bromo & Probolinggo</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5">Pilih Armada Mobil <span class="text-rose-400">*</span></label>
                        <select id="ord_armada" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500 font-semibold">
                            @foreach($vehicles as $v)
                                <option value="{{ $v->merk }} ({{ $v->plat_nomor }})">{{ $v->merk }} - {{ $v->plat_nomor }} ({{ $v->status }})</option>
                            @endforeach
                            <option value="Toyota HiAce Premio Luxury">Toyota HiAce Premio Luxury</option>
                            <option value="Toyota HiAce Commuter">Toyota HiAce Commuter</option>
                            <option value="Toyota Innova Zenix">Toyota Innova Zenix Hybrid</option>
                            <option value="Isuzu Elf Long Deluxe">Isuzu Elf Long Deluxe</option>
                            <option value="Bebas / Rekomendasi Owner">Bebas / Rekomendasi Owner</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5">Tanggal Sewa / Berangkat <span class="text-rose-400">*</span></label>
                        <input type="date" id="ord_tgl" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-1.5">Durasi Sewa <span class="text-rose-400">*</span></label>
                        <select id="ord_durasi" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500">
                            <option value="1 Hari">1 Hari</option>
                            <option value="2 Hari">2 Hari</option>
                            <option value="3 Hari">3 Hari</option>
                            <option value="4+ Hari / Mingguan">4+ Hari / Mingguan</option>
                            <option value="Sekali Jalan (Drop Off)">Sekali Jalan (Drop Off)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-slate-300 mb-1.5">Alamat Penjemputan di Probolinggo <span class="text-rose-400">*</span></label>
                        <textarea id="ord_penjemputan" rows="2" required placeholder="Contoh: Jl. Raya Bromo No. 45, Mayangan, Kota Probolinggo (Patokan dekat Alun-alun)" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-slate-300 mb-1.5">Kota & Lokasi Tujuan <span class="text-rose-400">*</span></label>
                        <input type="text" id="ord_tujuan" required placeholder="Contoh: Bandara Juanda Surabaya / Malang Kota / Bali" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-slate-300 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea id="ord_catatan" rows="2" placeholder="Contoh: Minta penjemputan jam 07.00 WIB pagi, membawa 4 koper." class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-emerald-500"></textarea>
                    </div>

                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-xl shadow-emerald-600/30 transition-all group">
                            <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">send</span>
                            Kirim Order Langsung ke WhatsApp Owner
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- ==================== KONTAK & FOOTER SECTION ==================== -->
    <footer id="kontak" data-aos="fade-up" class="pt-16 pb-12 bg-slate-950 border-t border-slate-800 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <div class="space-y-4 md:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 p-0.5 shadow-lg">
                            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-sky-400">
                                <span class="material-symbols-outlined text-2xl">directions_bus</span>
                            </div>
                        </div>
                        <span class="font-display font-black text-xl text-white">TravelManager</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-md">
                        Sistem Manajemen Operasional & Pemesanan Tiket Travel Eksekutif Terpercaya. Melayani travel rute antar kota, rental armada mobil, dan charter kendaraan keluarga.
                    </p>
                </div>

                <div class="space-y-3">
                    <h4 class="font-display font-bold text-sm text-white">Kontak Layanan Owner</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-emerald-400">call</span>
                            <span>WhatsApp: {{ $ownerPhone ?? '0896-2961-5301' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-sky-400">mail</span>
                            <span>Email: owner@travel.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-amber-400">schedule</span>
                            <span>Jam Ops: 24 Jam Setiap Hari</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="font-display font-bold text-sm text-white">Portal Akses</h4>
                    <p class="text-slate-400 leading-relaxed mb-2">Login bagi Pengemudi/Supir dan Manajemen Owner.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold transition-all shadow-md">
                        <span class="material-symbols-outlined text-base">login</span> Portal Login System
                    </a>
                </div>

            </div>

            <div class="pt-8 border-t border-slate-800 text-center text-slate-500 text-[11px]">
                &copy; {{ date('Y') }} TravelManager Executive Fleet System. All rights reserved.
            </div>

        </div>
    </footer>

    <!-- Floating WhatsApp Icon Pojok Kanan Bawah -->
    @php
        $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $ownerPhone ?? '089629615301');
        if (str_starts_with($cleanOwnerPhone, '0')) {
            $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
        }
    @endphp

    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 group">
        <!-- Hover Tooltip Pill -->
        <a href="https://wa.me/{{ $cleanOwnerPhone }}?text=Halo%20Owner%20TravelManager,%20saya%20ingin%20tanya%20sewa%20mobil%20harian%20penjemputan%20dari%20Probolinggo." 
           target="_blank" 
           class="hidden sm:flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900/95 text-emerald-400 font-bold text-xs border border-emerald-500/30 shadow-2xl backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none group-hover:pointer-events-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>Tanya Sewa Harian Dari Probolinggo ({{ $ownerPhone ?? '089629615301' }})</span>
        </a>

        <!-- Floating WhatsApp Icon -->
        <a href="https://wa.me/{{ $cleanOwnerPhone }}?text=Halo%20Owner%20TravelManager,%20saya%20ingin%20tanya%20sewa%20mobil%20harian%20penjemputan%20dari%20Probolinggo." 
           target="_blank" 
           title="Chat WhatsApp dengan Owner TravelManager"
           class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/40 border border-emerald-400/40 hover:scale-110 active:scale-95 transition-all duration-300 relative">
            <span class="material-symbols-outlined text-3xl">chat</span>
            <!-- Pulse Glow Ring -->
            <span class="absolute inset-0 rounded-2xl bg-emerald-400/30 animate-ping pointer-events-none"></span>
        </a>
    </div>

    <!-- AOS (Animate On Scroll) JS CDN -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50,
                easing: 'ease-out-cubic'
            });
        });

        function submitFormOrderToWA(event) {
            event.preventDefault();
            const nama = document.getElementById('ord_nama').value.trim();
            const wa = document.getElementById('ord_wa').value.trim();
            const layanan = document.getElementById('ord_layanan').value;
            const armada = document.getElementById('ord_armada').value;
            const tgl = document.getElementById('ord_tgl').value;
            const durasi = document.getElementById('ord_durasi').value;
            const penjemputan = document.getElementById('ord_penjemputan').value.trim();
            const tujuan = document.getElementById('ord_tujuan').value.trim();
            const catatan = document.getElementById('ord_catatan').value.trim();

            const ownerPhone = "{{ $cleanOwnerPhone }}";

            let message = `*PEMESANAN BARU - TRAVELMANAGER*\n\n`;
            message += `👤 *Nama Pemesan*: ${nama}\n`;
            message += `📱 *No. WhatsApp*: ${wa}\n`;
            message += `🚙 *Jenis Layanan*: ${layanan}\n`;
            message += `🚘 *Pilihan Armada*: ${armada}\n`;
            message += `📅 *Tgl Sewa*: ${tgl} (Durasi: ${durasi})\n`;
            message += `📍 *Penjemputan (Probolinggo)*: ${penjemputan}\n`;
            message += `🏁 *Kota Tujuan*: ${tujuan}\n`;
            if (catatan) {
                message += `📝 *Catatan*: ${catatan}\n`;
            }
            message += `\nMohon konfirmasi ketersediaan unit & total biayanya. Terima kasih!`;

            const url = `https://wa.me/${ownerPhone}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function bookingViaWhatsApp() {
            const asal = document.getElementById('hero_asal').value;
            const tujuan = document.getElementById('hero_tujuan').value;
            const ownerPhone = "{{ $cleanOwnerPhone }}";
            const text = encodeURIComponent(`Halo Owner TravelManager, saya ingin sewa mobil per hari dengan penjemputan dari ${asal} menuju ${tujuan}. Mohon info tarif sewa harian & ketersediaan armadanya. Terima kasih!`);
            window.open(`https://wa.me/${ownerPhone}?text=${text}`, '_blank');
        }
    </script>
</body>
</html>
