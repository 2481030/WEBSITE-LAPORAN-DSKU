<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Laporan Triwulan Pelayanan Jemaat — DSKU</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,500;1,9..144,600&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --maroon-950:#2c0a10;
            --maroon-900:#3d0e16;
            --maroon-800:#5c1420;
            --crimson-600:#9c1c2e;
            --crimson-500:#b8283a;
            --gold-500:#c9a24b;
            --gold-300:#e0c584;
            --cream-50:#faf6ef;
            --cream-100:#f3ecdf;
            --ink-900:#241a1a;
            --ink-600:#6b5c5c;
            --line:#e7ddca;
        }

        *{ scrollbar-width: thin; scrollbar-color: var(--gold-300) transparent; }

        body{
            background: var(--cream-50);
            color: var(--ink-900);
            font-family: 'Manrope', sans-serif;
        }

        .font-display{ font-family:'Fraunces', serif; }
        .font-mono{ font-family:'JetBrains Mono', monospace; }

        /* ---------- Sidebar ---------- */
        .sidebar{
            background: linear-gradient(180deg, var(--maroon-950) 0%, var(--maroon-900) 55%, var(--maroon-950) 100%);
            border-right: 1px solid rgba(201,162,75,0.25);
        }
        .brand-seal{
            width: 56px; height: 56px; border-radius: 999px;
            border: 1.5px solid var(--gold-300);
            display:flex; align-items:center; justify-content:center;
            position: relative;
            background: radial-gradient(circle at 30% 30%, rgba(201,162,75,0.18), transparent 70%);
        }
        .brand-seal::before{
            content:"";
            position:absolute; inset:-6px;
            border-radius:999px;
            border: 1px dashed rgba(201,162,75,0.45);
            animation: spin 40s linear infinite;
        }
        @media (prefers-reduced-motion: reduce){ .brand-seal::before{ animation:none; } }
        @keyframes spin{ to{ transform: rotate(360deg); } }

        .sidebar-link{
            color: rgba(243,236,223,0.62);
            border-left: 3px solid transparent;
            position: relative;
            transition: color .2s ease, background-color .2s ease, border-color .2s ease;
        }
        .sidebar-link:hover{
            color: var(--cream-100);
            background: rgba(201,162,75,0.08);
        }
        .sidebar-link.active{
            color: var(--cream-50);
            background: linear-gradient(90deg, rgba(201,162,75,0.16), transparent);
            border-left: 3px solid var(--gold-500);
        }
        .sidebar-link.active i{ color: var(--gold-300) !important; }
        .sidebar-eyebrow{
            font-family:'JetBrains Mono', monospace;
            letter-spacing: .14em;
            color: rgba(201,162,75,0.55);
        }

        /* ---------- Header ---------- */
        .glass-header{
            background: rgba(250,246,239,0.72);
            backdrop-filter: blur(14px) saturate(150%);
            -webkit-backdrop-filter: blur(14px) saturate(150%);
            border-bottom: 1px solid var(--line);
        }
        .meta-field{
            border-bottom: 1px solid var(--line);
            font-family:'JetBrains Mono', monospace;
            transition: border-color .2s ease;
        }
        .meta-field:focus{ outline:none; border-color: var(--crimson-500); }

        /* ---------- Cards ---------- */
        .kpi-card{
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .kpi-card::before{
            content:"";
            position:absolute; left:0; top:0; bottom:0; width:4px;
            background: linear-gradient(180deg, var(--crimson-600), var(--gold-500));
        }
        .kpi-card:hover{
            transform: translateY(-3px);
            box-shadow: 0 18px 30px -18px rgba(92,20,32,0.35);
        }

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
        }

        .btn-primary{
            background: linear-gradient(135deg, var(--crimson-600), var(--maroon-800));
            color:#fff;
            box-shadow: 0 10px 20px -10px rgba(92,20,32,0.55);
            transition: filter .2s ease, transform .15s ease;
        }
        .btn-primary:hover{ filter: brightness(1.08); transform: translateY(-1px); }

        /* ---------- Table ---------- */
        .report-table thead th{
            font-family:'JetBrains Mono', monospace;
            font-size: .68rem;
            letter-spacing:.08em;
            color: var(--ink-600);
            background: var(--cream-100);
            border-bottom: 1px solid var(--line);
        }
        .report-table tbody tr{ border-bottom: 1px solid var(--line); }
        .report-table tbody tr:hover{ background: rgba(201,162,75,0.06); }
        .report-table td{ color: var(--ink-900); }
        .report-input{
            font-family:'JetBrains Mono', monospace;
            background: var(--cream-100);
            border: 1px solid var(--line);
            border-radius: 8px;
            transition: border-color .2s ease, background-color .2s ease;
        }
        .report-input:focus{
            outline:none;
            border-color: var(--crimson-500);
            background:#fff;
        }
        .field-missing{
            border-color: var(--crimson-600) !important;
            background: #fdecec !important;
            box-shadow: 0 0 0 3px rgba(156,28,46,0.12);
        }

        .section-eyebrow{
            font-family:'JetBrains Mono', monospace;
            letter-spacing:.12em;
            color: var(--crimson-600);
            font-size:.7rem;
        }

        .dept-content{ display:none; }
        .dept-content.active{ display:block; }

        .tagline{ font-family:'Fraunces', serif; font-style: italic; }

        ::-webkit-scrollbar{ width:8px; }
        ::-webkit-scrollbar-thumb{ background: var(--gold-300); border-radius: 8px; }
    </style>
</head>
<body class="antialiased">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div class="sidebar w-64 flex flex-col h-full shadow-2xl z-20">
            <div class="p-6 flex flex-col items-center border-b border-white/5 gap-3">
                <div class="brand-seal">
                    <i class="fa-solid fa-dove text-lg" style="color: var(--gold-300);"></i>
                </div>
                <div class="text-center">
                    <span class="block text-lg font-display font-semibold text-white tracking-wide">DSKU Report</span>
                    <span class="block text-[11px] tagline text-white/50 mt-0.5">Sumatera Kawasan Utara</span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto py-4 space-y-1" id="sidebar-menu">
                <a href="#" onclick="switchTab('ringkasan')" class="sidebar-link active flex items-center px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-chart-pie w-5 mr-3 text-lg" style="color:var(--gold-300);"></i> Ringkasan Utama
                </a>
                <div class="px-6 pt-4 pb-1 text-[10px] font-semibold sidebar-eyebrow uppercase">Departemen</div>

                <a href="#" onclick="switchTab('sekolah-sabat')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-book-open w-5 mr-3 text-white/40"></i> Sekolah Sabat
                </a>
                <a href="#" onclick="switchTab('penginjilan-perorangan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-users-rays w-5 mr-3 text-white/40"></i> Penginjilan Perorangan
                </a>
                <a href="#" onclick="switchTab('penatalayanan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-hand-holding-dollar w-5 mr-3 text-white/40"></i> Penatalayanan
                </a>
                <a href="#" onclick="switchTab('rumah-tangga')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-house-chimney w-5 mr-3 text-white/40"></i> Rumah Tangga
                </a>
                <a href="#" onclick="switchTab('pelayanan-anak')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-child w-5 mr-3 text-white/40"></i> Pelayanan Anak-Anak
                </a>
                <a href="#" onclick="switchTab('komunikasi')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-bullhorn w-5 mr-3 text-white/40"></i> Komunikasi
                </a>
                <a href="#" onclick="switchTab('berkebutuhan-khusus')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-wheelchair w-5 mr-3 text-white/40"></i> Berkebutuhan Khusus
                </a>
                <a href="#" onclick="switchTab('bwa')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-person-dress w-5 mr-3 text-white/40"></i> Pelayanan BWA
                </a>
                <a href="#" onclick="switchTab('literatur')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-book w-5 mr-3 text-white/40"></i> Pelayanan Literatur
                </a>
                <a href="#" onclick="switchTab('roh-nubuat')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-scroll w-5 mr-3 text-white/40"></i> Roh Nubuat
                </a>
                <a href="#" onclick="switchTab('ndr')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-heart-pulse w-5 mr-3 text-white/40"></i> Pelayanan NDR
                </a>
                <a href="#" onclick="switchTab('pemuda-advent')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-people-group w-5 mr-3 text-white/40"></i> Pemuda Advent
                </a>
                <a href="#" onclick="switchTab('kesehatan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-kit-medical w-5 mr-3 text-white/40"></i> Pelayanan Kesehatan
                </a>
                <a href="#" onclick="switchTab('pendidikan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-graduation-cap w-5 mr-3 text-white/40"></i> Pendidikan
                </a>
            </div>

            <div class="p-5 border-t border-white/5 text-center">
                <span class="text-[10px] tagline text-white/35">"Melaporkan dengan setia, melayani dengan sukacita"</span>
            </div>
        </div>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <header class="glass-header px-8 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <p class="section-eyebrow uppercase">Laporan Resmi Triwulan</p>
                    <h1 class="text-xl font-display font-semibold" style="color:var(--maroon-900);">Pelayanan Jemaat</h1>
                    <p class="text-xs" style="color:var(--ink-600);">Daerah Sumatera Kawasan Utara</p>
                </div>

                <div class="flex space-x-5 bg-white/60 px-4 py-2.5 rounded-xl border" style="border-color:var(--line);">
                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase tracking-wider" style="color:var(--ink-600);">Pendeta</label>
                        <input type="text" id="header-pendeta" placeholder="—" class="meta-field bg-transparent text-sm w-24 focus:outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase tracking-wider" style="color:var(--ink-600);">Distrik</label>
                        <input type="text" id="header-distrik" placeholder="—" class="meta-field bg-transparent text-sm w-20 focus:outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase tracking-wider" style="color:var(--ink-600);">Triwulan</label>
                        <input type="text" id="header-triwulan" placeholder="—" class="meta-field bg-transparent text-sm w-12 focus:outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase tracking-wider" style="color:var(--ink-600);">Tahun</label>
                        <input type="number" id="header-tahun" value="2026" class="meta-field bg-transparent text-sm w-14 focus:outline-none">
                    </div>
                </div>
            </header>

            <main class="p-8 max-w-7xl w-full mx-auto flex-1">

                <!-- RINGKASAN -->
                <div id="ringkasan" class="dept-content active space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="kpi-card p-6 flex items-center justify-between">
                            <div class="pl-2">
                                <p class="text-xs uppercase tracking-wide" style="color:var(--ink-600);">Total Anggota Jemaat</p>
                                <h3 class="text-3xl font-display font-semibold mt-1" style="color:var(--maroon-900);">1,240</h3>
                                <span class="text-xs font-semibold" style="color:#1a7a4c;"><i class="fa-solid fa-arrow-up"></i> +4.2% triwulan lalu</span>
                            </div>
                            <div class="p-3 rounded-xl" style="background:var(--cream-100); color:var(--crimson-600);"><i class="fa-solid fa-users text-2xl"></i></div>
                        </div>
                        <div class="kpi-card p-6 flex items-center justify-between">
                            <div class="pl-2">
                                <p class="text-xs uppercase tracking-wide" style="color:var(--ink-600);">Total Baptisan Baru</p>
                                <h3 class="text-3xl font-display font-semibold mt-1" style="color:var(--maroon-900);">32</h3>
                                <span class="text-xs font-semibold" style="color:var(--crimson-600);">Target tercapai 80%</span>
                            </div>
                            <div class="p-3 rounded-xl" style="background:var(--cream-100); color:var(--crimson-600);"><i class="fa-solid fa-water text-2xl"></i></div>
                        </div>
                        <div class="kpi-card p-6 flex items-center justify-between">
                            <div class="pl-2">
                                <p class="text-xs uppercase tracking-wide" style="color:var(--ink-600);">Anggota Aktif SS</p>
                                <h3 class="text-3xl font-display font-semibold mt-1" style="color:var(--maroon-900);">850</h3>
                                <span class="text-xs" style="color:var(--ink-600);">Kehadiran rata-rata</span>
                            </div>
                            <div class="p-3 rounded-xl" style="background:var(--cream-100); color:var(--crimson-600);"><i class="fa-solid fa-book-bookmark text-2xl"></i></div>
                        </div>
                        <div class="kpi-card p-6 flex items-center justify-between">
                            <div class="pl-2">
                                <p class="text-xs uppercase tracking-wide" style="color:var(--ink-600);">KPA / Kelompok Kecil</p>
                                <h3 class="text-3xl font-display font-semibold mt-1" style="color:var(--maroon-900);">18</h3>
                                <span class="text-xs font-semibold" style="color:#1a7a4c;"><i class="fa-solid fa-plus"></i> 2 Unit baru</span>
                            </div>
                            <div class="p-3 rounded-xl" style="background:var(--cream-100); color:var(--crimson-600);"><i class="fa-solid fa-circle-nodes text-2xl"></i></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="panel p-6">
                            <p class="section-eyebrow uppercase mb-1">Demografi</p>
                            <h4 class="text-md font-display font-semibold mb-4" style="color:var(--maroon-900);">Anggota Sekolah Sabat</h4>
                            <div class="h-64 flex justify-center"><canvas id="ssDemographicChart"></canvas></div>
                        </div>
                        <div class="panel p-6">
                            <p class="section-eyebrow uppercase mb-1">Tren</p>
                            <h4 class="text-md font-display font-semibold mb-4" style="color:var(--maroon-900);">Kehadiran Ibadah Triwulan Ini</h4>
                            <div class="h-64"><canvas id="attendanceChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- SEKOLAH SABAT -->
                <div id="sekolah-sabat" class="dept-content space-y-6">
                    <div class="flex justify-between items-center panel px-6 py-4">
                        <div>
                            <p class="section-eyebrow uppercase">Departemen</p>
                            <h2 class="text-lg font-display font-semibold" style="color:var(--maroon-900);"><i class="fa-solid fa-book-open mr-2" style="color:var(--crimson-600);"></i> Sekolah Sabat</h2>
                        </div>
                        <button onclick="saveData()" class="btn-primary px-5 py-2.5 rounded-lg text-sm font-medium"><i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Laporan</button>
                    </div>

                    <div class="panel overflow-hidden">
                        <table class="w-full border-collapse text-left text-sm report-table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 w-12 uppercase">No</th>
                                    <th class="px-6 py-3 uppercase">Indikator Pelayanan</th>
                                    <th class="px-6 py-3 w-48 text-center uppercase">Jumlah / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td class="px-6 py-3 font-medium">1</td><td class="px-6 py-3">Jumlah anggota SS</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">2</td><td class="px-6 py-3">Beginner (Anak Balita)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">3</td><td class="px-6 py-3">Kindergarten</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">4</td><td class="px-6 py-3">Primary (SD)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">5</td><td class="px-6 py-3">Junior (Setingkat SD)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">6</td><td class="px-6 py-3">Earliteen (Remaja)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">7</td><td class="px-6 py-3">Youth (Pemuda)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">8</td><td class="px-6 py-3">Adult (Dewasa)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">9</td><td class="px-6 py-3">Jumlah UKSS (Unit Kecil Sekolah Sabat)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">10</td><td class="px-6 py-3">Jumlah guru SS di Jemaat</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">11</td><td class="px-6 py-3">Jumlah guru SS yang sudah dilatih</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">12</td><td class="px-6 py-3">Jumlah anggota UKSS yang mendoakan 1 jiwa atau lebih</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">13</td><td class="px-6 py-3">Jumlah target yang didoakan oleh anggota UKSS</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">14</td><td class="px-6 py-3">Jumlah anggota UKSS yang sudah peduli pada target 1 jiwa per keluarga</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">15</td><td class="px-6 py-3">Jumlah jiwa yang sudah dibawa ke KPA oleh anggota SS</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">16</td><td class="px-6 py-3">Jumlah komite SS triwulan ini</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">17</td><td class="px-6 py-3">Melakukan ibadah SS Anak-anak terpisah dari SS dewasa?</td><td class="px-6 py-2">
                                    <select class="report-input w-full p-2 text-center"><option>Ya</option><option>Tidak</option></select>
                                </td></tr>
                                <tr><td class="px-6 py-3 font-medium">18</td><td class="px-6 py-3">Jumlah anggota aktif membaca Alkitab tiap hari (Follow the Bible)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">19</td><td class="px-6 py-3">Jumlah anggota yang hadir di acara SS</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">20</td><td class="px-6 py-3">Jumlah anggota yang hadir tepat waktu di acara SS</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">21</td><td class="px-6 py-3">Jumlah pelatihan dan seminar SS yang dilakukan triwulan ini</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">22</td><td class="px-6 py-3">Jumlah anggota yang hadir pada Sabat ke-2</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">23</td><td class="px-6 py-3">Jumlah anggota yang hadir pada Sabat ke-7</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">24</td><td class="px-6 py-3">Jumlah anggota yang Renungan Pagi setiap hari</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">25</td><td class="px-6 py-3">Jumlah anggota yang belajar SS setiap hari</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">26</td><td class="px-6 py-3">Jumlah anggota yang hadir Rabu malam</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">27</td><td class="px-6 py-3">Jumlah anggota yang hadir Jumat malam/Vesper</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">28</td><td class="px-6 py-3">Jumlah anggota yang melakukan doa 777 dan 1752</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">29</td><td class="px-6 py-3">Jumlah KK yang aktif</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PENGINJILAN PERORANGAN -->
                <div id="penginjilan-perorangan" class="dept-content space-y-6">
                    <div class="flex justify-between items-center panel px-6 py-4">
                        <div>
                            <p class="section-eyebrow uppercase">Departemen</p>
                            <h2 class="text-lg font-display font-semibold" style="color:var(--maroon-900);"><i class="fa-solid fa-users-rays mr-2" style="color:var(--crimson-600);"></i> Penginjilan Perorangan</h2>
                        </div>
                        <button onclick="saveData()" class="btn-primary px-5 py-2.5 rounded-lg text-sm font-medium">Simpan</button>
                    </div>
                    <div class="panel overflow-hidden">
                        <table class="w-full text-left text-sm report-table">
                            <thead>
                                <tr><th class="px-6 py-3 w-12 uppercase">No</th><th class="px-6 py-3 uppercase">Indikator</th><th class="px-6 py-3 w-48 text-center uppercase">Data Input</th></tr>
                            </thead>
                            <tbody>
                                <tr><td class="px-6 py-3">1</td><td class="px-6 py-3">Jumlah anggota yang terlibat dalam penginjilan</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">2</td><td class="px-6 py-3">Jumlah penginjilan di jemaat</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">3</td><td class="px-6 py-3">Jumlah buku penginjilan yang dibagikan</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">4</td><td class="px-6 py-3">Baptisan hasil kaum awam</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">5a</td><td class="px-6 py-3">Bible Correspondence School: Enrollment (Pendaftar)</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">5b</td><td class="px-6 py-3">Bible Correspondence School: Graduate (Tamat)</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">5c</td><td class="px-6 py-3">Bible Correspondence School: Baptis</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">6</td><td class="px-6 py-3">Community Service Unit</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3">7</td><td class="px-6 py-3">Jumlah Pelmas</td><td><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PLACEHOLDER GENERIK -->
                <div id="generic-placeholder" class="dept-content panel p-8 text-center space-y-4">
                    <div class="p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto" style="background:var(--cream-100); color:var(--crimson-600);"><i class="fa-solid fa-folder-open text-2xl"></i></div>
                    <p class="section-eyebrow uppercase" id="placeholder-eyebrow">Departemen</p>
                    <h3 class="text-lg font-display font-semibold" style="color:var(--maroon-900);" id="placeholder-title">Nama Departemen</h3>
                    <p class="text-sm max-w-md mx-auto tagline" style="color:var(--ink-600);">Formulasi data dan tabel input terintegrasi otomatis untuk triwulan berjalan sesuai template dokumen resmi DSKU.</p>
                    <div class="overflow-x-auto text-left mt-6">
                        <table class="w-full border-collapse text-sm report-table">
                            <thead>
                                <tr><th class="px-6 py-3 w-12 uppercase text-left">No</th><th class="px-6 py-3 uppercase text-left">Indikator Kinerja Departemen</th><th class="px-6 py-3 w-48 text-center uppercase">Nilai Triwulan</th></tr>
                            </thead>
                            <tbody id="placeholder-table-body"></tbody>
                        </table>
                    </div>
                    <div class="flex justify-end pt-4"><button onclick="saveData()" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Simpan Data</button></div>
                </div>

            </main>
        </div>
    </div>

    <script>
        const departmentDataFields = {
            'penatalayanan': ["Jumlah anggota jemaat.", "Jumlah seminar Penatalayanan.", "Jumlah kegiatan promosi Penatalayanan yang dilaksanakan.", "Jumlah keluarga yang dikunjungi dan diberi program sosialisasi Penatalayanan.", "Jumlah sertifikasi yang dilaksanakan untuk Pendidikan Dasar Penatalayanan.", "Jumlah Pendeta/Direktur/Administrator/Pemimpin Jemaat Lokal sebagai Pendidik Dasar Penatalayanan.", "Jumlah khotbah Penatalayanan tentang Penatalayanan yang Alkitabiah.", "Jumlah unit pembayar persepuluhan.", "Jumlah gereja yang melakukan promosi video persepuluhan dan persembahan Penatalayanan.", "Jumlah anggota jemaat yang menerapkan rencana persembahan gabungan.", "Jumlah Pendeta/pekerja yang menerapkan rencana persembahan gabungan."],
            'rumah-tangga': ["Keluarga muda (0-10 Thn)", "Keluarga madya (11-20 Thn)", "Keluarga dewasa (>21 Thn)", "Orangtua tunggal", "Jumlah keluarga GMAHK yang belajar Alkitab secara regular.", "Jumlah keluarga GMAHK yang mengadakan kebaktian pagi tiap hari.", "Jumlah keluarga GMAHK yang mengadakan kebaktian malam tiap hari.", "Jumlah keluarga yang berpartisipasi dalam doa 777.", "Jumlah keluarga yang berpartisipasi menghafal ayat hafalan.", "Jumlah jemaat yang melaksanakan Pekan Doa RT.", "Jumlah jemaat yang melaksanakan Sabat Pernikahan dan RT Kristen.", "Jumlah jemaat yang melaksanakan Sabat Bersama keluarga.", "Jumlah orangtua memiliki waktu sendiri bersama Tuhan.", "Jumlah retreat/perkemahan keluarga.", "Jumlah seminar untuk orangtua.", "Jumlah seminar untuk memperkaya pernikahan.", "Jumlah seminar untuk orang muda yang dan akan bertunangan.", "Jumlah seminar untuk yang single.", "Jumlah penyuluhan pernikahan.", "Jumlah pelayanan untuk manula/lansia.", "Jumlah pelayanan konseling melalui telepon.", "Jumlah pelmas (kerja bakti/charity clinic)", "Jumlah kelompok kecil/KPA RT.", "Jumlah KKR RT.", "Jumlah baptisan"],
            'pelayanan-anak': ["Jumlah jemaat yang mempunyai coordinator pelayanan anak-anak.", "Jumlah anak Baby Step (0-12 Bln)", "Jumlah anak Beginner (1-3 Thn)", "Jumlah anak Kindergarten (4-6 Thn)", "Jumlah anak Primary (7-9 Thn)", "Jumlah anak Junior (10-12 Thn)", "Jumlah anak Earliteen (13-14 Thn)", "Jumlah guru kelas Baby Step", "Jumlah guru kelas Beginner", "Jumlah guru kelas Kindergarten", "Jumlah guru kelas Primary", "Jumlah guru kelas Junior/Power Point", "Jumlah guru kelas Earliteen", "Sekolah Alkitab Liburan (SAL) — Offline", "Sekolah Alkitab Liburan (SAL) — Online", "Program jangkauan keluar anak: Panti Asuhan/Werda", "Program jangkauan keluar anak: Melayani siaran radio", "Kerja bakti, welcome baby, keterampilan, dll.", "Jumlah jemaat yang mengadakan kelas baptisan pada anak kurang dari 15 tahun.", "Jumlah anak yang sudah dibaptis berumur kurang dari 15 tahun.", "Jumlah jangkauan keluar dan pelayanan kepada masyarakat yang melibatkan anak.", "Seminar pelatihan pemimpin/guru SS Anak-anak (Online)", "Seminar pelatihan pemimpin/guru SS Anak-anak (Offline)", "Pelatihan pemuridan kepada anak-anak (KID – Kids In Discipleship)", "Jumlah orangtua yang mendampingi kelas Baby Step (0-12 bln)."],
            'komunikasi': ["Apakah Poster Mission Reach26 sudah sampai ke Group WA Jemaat?", "Berapa Banyak Jemaat Yang Sudah Membuat Misi & Visi GMAHK Se-Dunia?", "Berapa Banyak Jemaat Yang Sudah Membuat Spanduk Program Mission Reach 2026?", "Berapa Banyak Anggota Jemaat Turut Membagikan Firman Tuhan melalui Media Sosial?", "Berapa banyak Anggota Jemaat membagikan Firman Tuhan ke Group WhatsApp Non Advent?", "Berapa Kali Mengadakan Siaran Radio di wilayah anda?", "Berapa jumlah jam siaran digunakan?", "Berapa jumlah anggota Jemaat yang mendengarkan dan membagikan Link Adventist World Radio (AWR)?", "Menyaksikan Hope Channel Indonesia melalui Youtube", "Menyaksikan Hope Channel Indonesia melalui Facebook", "Menyaksikan Hope Channel Indonesia melalui Satelit Parabola", "Apakah Jemaat Sudah Memiliki Website?", "Apa nama Website Jemaat Anda?", "Berapa banyak anggota jemaat yang terlibat dalam penginjilan Digital?", "Nama Akun Jemaat di Facebook", "Nama Channel Jemaat di Youtube", "Nama Akun Instagram Jemaat", "Nama Akun Tiktok Jemaat", "Berapa banyak Jemaat yang sudah memproduksi rekaman Video (Lagu rohani, Kesaksian, Renungan, dll)?", "Berapa kali Pelatihan Pembuatan Konten Rohani Digital di Jemaat?", "Berapa Jemaat Yang Sudah Mengadakan Ibadah/Kebaktian atau KKR Live Streaming?", "Berapa kali kegiatan berkenalan dengan FKUB, Pemerintah setempat atau tokoh Masyarakat?", "Berapa kali kegiatan berkenalan dengan Tokoh Agama atau Pendeta Gereja Non Advent?", "Jumlah Anggota Jemaat yang Berpendidikan dalam bidang Hukum?", "Jumlah Seminar Komunikasi di Jemaat?", "Berapa jumlah Peserta Seminar Komunikasi di Jemaat?", "Berapa jumlah Jemaat yang sudah Membuat Budget/Anggaran Departemen Komunikasi Jemaat?", "Berapa Jemaat Yang Sudah membentuk Tim Digital Evangelism/Cell Phone Evangelism?", "Reach Through Digital Missions 2026: Orang yang sudah didoakan Non Advent secara online", "Reach Through Digital Missions 2026: Orang yang sudah menunjukkan simpati", "Reach Through Digital Missions 2026: Jumlah sahabat rohani melalui Media Sosial", "Reach Through Digital Missions 2026: Orang yang sudah dilayani kebutuhannya secara digital", "Reach Through Digital Missions 2026: Orang Non SDA yang sedang mengikuti Pelajaran Alkitab/KPA Online", "Reach Through Digital Missions 2026: Orang yang sudah dibaptiskan berkat Cell Phone Evangelism", "Berapa Jumlah Jemaat Yang lokasi Gerejanya Sudah Terdaftar di Google Map?", "Mengirimkan Berita Kegiatan Pelayanan ke Group WA Komunikasi/Warta DSKU?", "Berapa banyak Jemaat yang sudah membuat Buletin Digital di Jemaat?", "Berapa Jemaat Yang sudah membuat Podcast Rohani?", "Nama Pemimpin Komunikasi Jemaat (Nama & HP/WA)", "Hal-hal lain yang sudah dilakukan Jemaat untuk Cell Phone Evangelism"],
            'berkebutuhan-khusus': ["Cacat melihat/buta (Jumlah Jiwa)", "Cacat mendengar/tuli (Jumlah Jiwa)", "Cacat berbicara/bisu (Jumlah Jiwa)", "Autis/down syndrome (Jumlah Jiwa)", "Kesulitan mengurus diri sendiri/cacat fisik (Jumlah Jiwa)"],
            'bwa': ["Retreat BWA yang terlaksana", "Jumlah Wanita SDA yang hadir pada retreat BWA", "Jumlah Wanita Non SDA yang hadir pada retreat BWA", "Pelatihan dan seminar", "Bea siswa dari BWA Jemaat", "Jumlah BWA yang hadir pada sertifikasi kepemimpinan.", "Jumlah yang ditamatkan dari sertifikasi kepemimpinan.", "Jumlah baptisan KKR BWA", "Jumlah anggota BWA yang murtad dan ditarik kembali", "Jumlah pertemuan penginjilan BWA.", "Khotbah khusus BWA"],
            'literatur': ["Jumlah penginjil literatur di jemaat.", "Jumlah khotbah literatur.", "Jumlah Rally day oleh PL jemaat setempat.", "Jumlah anggota berlangganan RTK.", "Jumlah anggota berlangganan koran, majalah, dll.", "Jumlah biaya berlangganan TV kabel, majalah per bulan.", "Jumlah pemuda yang pasif (menganggur) tamat SMA.", "Jumlah keluarga yang tidak ada pekerjaan tetap.", "Jumlah promosi pelayanan literatur.", "Jumlah anggota jemaat terlibat jadi PL.", "Jumlah siswa yang terlibat PL.", "Jumlah KKR literatur jemaat.", "Jumlah baptisan hasil kontak PL Jemaat.", "Jumlah seminar/workshop penerbitan/literatur.", "Jumlah peserta seminar."],
            'roh-nubuat': ["Khotbah Roh Nubuat", "Minggu sembahyang Roh Nubuat", "Sabat Roh Nubuat", "Seminar Roh Nubuat", "Penggunaan buku Roh Nubuat: Kebaktian Rabu Malam", "Penggunaan buku Roh Nubuat: Kebaktian buka Sabat/Vesper", "Penggunaan buku Roh Nubuat: Kebaktian Pemuda Advent", "Penggunaan buku Roh Nubuat: Kebaktian kelompok kecil", "Penggunaan buku Roh Nubuat: Kebaktian rumah tangga", "Penggunaan buku Roh Nubuat: Kebaktian BWA", "Promosi buku-buku Roh Nubuat", "Anggota memiliki buku: Para Nabi dan Bapa/Sejarah Para Nabi", "Anggota memiliki buku: Para Nabi dan Raja", "Anggota memiliki buku: Kerinduan Segala Zaman", "Anggota memiliki buku: Kemenangan Akhir", "Anggota memiliki buku: Rumah Tangga Advent", "Anggota memiliki buku: Amanat kepada Orang Muda", "Anggota memiliki buku: Nasihat bagi Sidang", "Anggota memiliki buku: Penuntun Pelayan Kristen", "Anggota memiliki buku: Hidup yang Terbaik", "Anggota memiliki buku: Khotbah di atas Bukit", "Anggota memiliki buku: Mendidik dan Membimbing Anak", "Anggota memiliki buku: Nasihat kepada Pendeta dan Pelayan Injil", "Anggota memiliki buku: Pelayan Injil", "Anggota memiliki buku: Peristiwa-peristiwa Akhir Zaman", "Anggota memiliki buku: Petunjuk Diet dan Makanan Anda", "Anggota memiliki buku: Riwayat Yesus", "Anggota memiliki buku: Perumpamaan Tuhan Yesus"],
            'ndr': ["Jumlah seminar/pelatihan yang dilakukan oleh dept. NDR/IEL", "Jumlah Care group baru.", "Jumlah Care group yang sudah ada.", "Jumlah care group yang melakukan jangkauan keluar.", "Jumlah tamu non SDA yang dibawa hadir ke gereja.", "Jumlah tamu non SDA yang ditindaklanjuti untuk dilayani.", "Jumlah anggota murtad yang dimenangkan kembali.", "Jumlah baptisan melalui care group."],
            'pemuda-advent': ["Jumlah klub Adventurer yang berjalan", "Jumlah Pembina Adventurer", "Anggota Adventurer (4-9 Tahun)", "Jumlah klub Pathfinder yang berjalan", "Jumlah Pembina Pathfinder", "Anggota Pathfinder (10-15 tahun)", "Jumlah Klub Master Guide", "Anggota Master Guide", "Calon Master Guide (16 tahun ke atas)", "Jumlah Pemuda yang sudah SYL", "Jumlah pelatihan SYL yang sedang berjalan", "Jumlah peserta yang sedang ikut SYL", "Jumlah Pemuda yang sudah Ambasador", "Jumlah klub Ambasador yang berjalan", "Jumlah anggota klub Ambasador", "Jumlah Pemuda Dewasa (21-31+ tahun) di jemaat", "Jumlah perkumpulan PA Dewasa yang berjalan", "Jumlah Mahasiswa yang berkuliah di P. Tinggi non Advent (PCM)", "Anggota PA Junior yang ikut Kepengurusan Jemaat", "Anggota PA Senior yang ikut Kepengurusan Jemaat", "Anggota PA yang Berdoa Setiap Hari", "Anggota PA ikut Youth Prayer Network", "Anggota PA melakukan Renungan Pagi", "Anggota PA yang membaca Alkitab secara teratur", "Anggota PA membaca buku Roh Nubuat secara teratur", "Anggota PA memiliki buku Sekolah Sabat dan belajar teratur", "Anggota PA yang aktif mengikuti acara PA pada Sabat sore", "Jumlah PA yang mengikuti 10 hari berdoa", "Jumlah PA yang ikut Pekan Doa", "Anggota PA yang terlibat dalam pelayanan Sabat ke-7", "Klub PA Junior/Senior yang berjalan setiap Sabat", "Anggota PA yang mengikuti Pelatihan/seminar", "Anggota PA yang melayani di hari Sabat", "Anggota PA yang ikut dalam vocal grup (paduan suara)", "Anggota PA yang ikut melawat", "Jumlah pemuda yang hadir/terlibat di KPA Pemuda/KOM", "Jumlah pemuda yang mengadakan/terlibat KKR Pemuda/VoY", "Jumlah pemuda yang mengadakan kegiatan sosial/pelmas", "Jumlah pemuda yang kembali ke gereja yang sudah lama hilang", "Jumlah pemuda yang terlibat pembagian buku penginjilan", "Jumlah pemuda yang dibaptiskan", "Kegiatan-kegiatan lain PA di Jemaat (Nama/Tempat/Tanggal/Jumlah peserta)", "Jumlah baptisan hasil KKR pemuda.", "Jumlah pelantikan kelas-kelas kemajuan."],
            'kesehatan': ["Petunjuk prinsip Kesehatan.", "Menyediakan bahan khotbah Kesehatan", "Mengadakan seminar Kesehatan", "Sertifikasi Kesehatan", "Mengadakan pekan doa Kesehatan", "Promosi membaca buku: Petunjuk Diet dan Makanan Anda", "Promosi membaca buku: Hidup yang Terbaik", "Mengadakan hari doa dan puasa", "Mengadakan demo/kelas memasak", "Mempromosikan makanan sehat", "Petunjuk perawatan gigi", "Petunjuk pencegahan: Penyakit jantung", "Petunjuk pencegahan: Kanker", "Petunjuk pencegahan: Bahaya obat-obatan", "Mengadakan evangelisasi Kesehatan.", "Mengadakan siaran radio dan TV", "Mengadakan charity clinic", "Mengadakan health expo", "Mengadakan pelayanan Kesehatan di mall/pusat perbelanjaan.", "Mengadakan kampanye kesehatan", "Mengadakan seminar tanpa tembakau/alkohol", "Mengadakan KKR", "Mengadakan KPA Kesehatan", "Mengadakan olahraga bersama", "Mengadakan pembagian traktat/sticker Kesehatan.", "Jumlah gereja yang menyajikan makanan vegetarian saat potluck.", "Jumlah anggota jemaat yang sudah vegetarian."],
            'pendidikan': ["Jumlah guru indeks", "Jumlah guru non indeks", "Jumlah guru SDA", "Jumlah guru non SDA", "Jumlah siswa SDA", "Jumlah siswa non SDA", "Jumlah guru yang belum mengikuti summer school.", "Jumlah KPA", "Jumlah baptisan"]
        };

        function switchTab(tabId) {
            document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('active'));

            if (window.event && window.event.currentTarget) {
                window.event.currentTarget.classList.add('active');
            }

            document.querySelectorAll('.dept-content').forEach(content => content.classList.remove('active'));

            const specificSection = document.getElementById(tabId);
            if (specificSection) {
                specificSection.classList.add('active');
            } else {
                const placeholder = document.getElementById('generic-placeholder');
                const title = document.getElementById('placeholder-title');
                const eyebrow = document.getElementById('placeholder-eyebrow');
                const tableBody = document.getElementById('placeholder-table-body');

                const formattedTitle = tabId.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                title.innerText = formattedTitle;
                eyebrow.innerText = 'Departemen';

                tableBody.innerHTML = '';
                const fields = departmentDataFields[tabId] || ["Jumlah kegiatan / Program Triwulan ini"];

                fields.forEach((field, index) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td class="px-6 py-4 font-medium">${index + 1}</td>
                            <td class="px-6 py-4"><span class="indikator-nama">${field}</span></td>
                            <td class="px-6 py-2">
                                <input type="number" class="report-input w-full p-2 text-center" value="0">
                            </td>
                        </tr>
                    `;
                });

                placeholder.classList.add('active');
            }
        }

        // FUNGSI SIMPAN UTAMA YANG SUDAH DIKOREKSI DAN DIINTEGRASIKAN DENGAN SWEETALERT2 3D
        function saveData() {
            // 1. Dapatkan nama departemen aktif dari tab menu/h2/h3
            const activeContent = document.querySelector('.dept-content.active');
            if (!activeContent || activeContent.id === 'ringkasan') {
                Swal.fire({ title: 'Peringatan!', text: 'Pilih departemen pelayanan terlebih dahulu.', icon: 'warning', confirmButtonColor: '#5c1420' });
                return;
            }

            let deptName = activeContent.id;
            const headingEl = activeContent.querySelector('h2') || document.getElementById('placeholder-title');
            if (headingEl) {
                deptName = headingEl.innerText.trim();
            }

            // 2. Koreksi Target Pembacaan ID Header Dashboard sesuai Struktur HTML
            const pendeta = document.getElementById('header-pendeta') ? document.getElementById('header-pendeta').value : '-';
            const distrik = document.getElementById('header-distrik') ? document.getElementById('header-distrik').value : '-';
            const triwulan = document.getElementById('header-triwulan') ? document.getElementById('header-triwulan').value : '-';
            const tahun = document.getElementById('header-tahun') ? document.getElementById('header-tahun').value : '2026';

            // 3. Modul Pemindaian Baris Tabel yang Fleksibel & Kebal Crash
            const rows = activeContent.querySelectorAll('tbody tr');
            let reportData = [];
            let missingFields = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 2) {
                    const spanEl = cells[1].querySelector('.indikator-nama');
                    const indikator = spanEl ? spanEl.innerText.trim() : cells[1].innerText.trim();

                    let nilai = '0';
                    let inputEl = null;
                    if (cells[2]) {
                        inputEl = cells[2].querySelector('input, select');
                        nilai = inputEl ? inputEl.value : '0';
                    }

                    if (indikator && isNaN(indikator)) {
                        // Anggap belum lengkap jika input kosong ATAU (untuk kolom angka) masih bernilai 0
                        const kosong = inputEl && nilai.toString().trim() === '';
                        const masihNol = inputEl && inputEl.tagName === 'INPUT' && inputEl.type === 'number' && nilai.toString().trim() !== '' && Number(nilai) === 0;

                        if (kosong || masihNol) {
                            missingFields.push(indikator);
                            if (inputEl) inputEl.classList.add('field-missing');
                        } else if (inputEl) {
                            inputEl.classList.remove('field-missing');
                        }
                        reportData.push({ indikator: indikator, nilai: nilai });
                    }
                }
            });

            if (reportData.length === 0) {
                Swal.fire({ title: 'Kosong!', text: 'Tidak ada indikator laporan pelayanan yang terdeteksi.', icon: 'info', confirmButtonColor: '#5c1420' });
                return;
            }

            // 3b. Validasi Kelengkapan Data Header + Tabel
            const headerMissing = [];
            if (!pendeta || pendeta.trim() === '') headerMissing.push('Pendeta');
            if (!distrik || distrik.trim() === '') headerMissing.push('Distrik');
            if (!triwulan || triwulan.trim() === '') headerMissing.push('Triwulan');
            if (!tahun || tahun.toString().trim() === '') headerMissing.push('Tahun');

            if (headerMissing.length > 0 || missingFields.length > 0) {
                let pesan = '';
                if (headerMissing.length > 0) {
                    pesan += `<p class="mb-2">Kolom identitas belum diisi: <b>${headerMissing.join(', ')}</b></p>`;
                }
                if (missingFields.length > 0) {
                    const daftar = missingFields.slice(0, 6).join(', ') + (missingFields.length > 6 ? `, dan ${missingFields.length - 6} lainnya` : '');
                    pesan += `<p>Indikator berikut belum diisi: <b>${daftar}</b></p>`;
                }
                Swal.fire({
                    title: 'Data Belum Lengkap!',
                    html: pesan,
                    icon: 'warning',
                    confirmButtonColor: '#9c1c2e',
                    confirmButtonText: 'Lengkapi Data'
                });
                return;
            }

            // Pemicu Animasi Loading Awal
            Swal.fire({
                title: 'Sedang Mengirim Data...',
                text: 'Menyimpan laporan langsung ke Google Sheets.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // 4. Pengiriman Data Menggunakan Jalur Relatif Laravel
            fetch('/laporan/simpan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    pendeta: pendeta,
                    distrik: distrik,
                    triwulan: triwulan,
                    tahun: tahun,
                    departemen: deptName,
                    data: reportData
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP Status: ' + response.status);
                return response.json();
            })
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data anda berhasil di kirim ke Google Sheets.',
                        icon: 'success',
                        showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                        hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' },
                        confirmButtonColor: '#5c1420',
                        confirmButtonText: 'Mantap'
                    });
                } else {
                    Swal.fire({ title: 'Gagal!', text: res.message || 'Gagal menyimpan laporan.', icon: 'error', confirmButtonColor: '#9c1c2e' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ title: 'Koneksi Gagal!', text: 'Gagal tersambung ke backend Laravel. Hubungkan kembali Wi-Fi Anda.', icon: 'warning', confirmButtonColor: '#c9a24b' });
            });
        }

        // Hapus highlight "belum lengkap" begitu user mengisi kolom dengan nilai yang valid (bukan kosong, dan bukan 0 untuk kolom angka)
        document.addEventListener('input', function(e) {
            const el = e.target;
            if (!el.matches('input, select') || !el.classList.contains('field-missing')) return;
            const val = el.value.toString().trim();
            const isEmpty = val === '';
            const isZero = el.tagName === 'INPUT' && el.type === 'number' && !isEmpty && Number(val) === 0;
            if (!isEmpty && !isZero) {
                el.classList.remove('field-missing');
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const ctxDemographic = document.getElementById('ssDemographicChart');
            if (ctxDemographic) {
                new Chart(ctxDemographic.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Anak-anak (SSAA)', 'Remaja (Earliteen)', 'Pemuda (Youth)', 'Dewasa (Adult)'],
                        datasets: [{
                            data: [120, 95, 210, 425],
                            backgroundColor: ['#9c1c2e', '#c9a24b', '#5c1420', '#e0c584'],
                            borderColor: '#faf6ef',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { family: 'Manrope' }, color: '#241a1a' } } }
                    }
                });
            }

            const ctxAttendance = document.getElementById('attendanceChart');
            if (ctxAttendance) {
                new Chart(ctxAttendance.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Sabat ke-1', 'Sabat ke-3', 'Sabat ke-5', 'Sabat ke-7', 'Sabat ke-9', 'Sabat ke-12'],
                        datasets: [
                            {
                                label: 'Sekolah Sabat Pagi',
                                data: [780, 810, 850, 890, 840, 860],
                                borderColor: '#9c1c2e',
                                backgroundColor: 'rgba(156, 28, 46, 0.08)',
                                fill: true,
                                tension: 0.35
                            },
                            {
                                label: 'Rabu Malam (Doa)',
                                data: [320, 340, 310, 390, 350, 380],
                                borderColor: '#c9a24b',
                                backgroundColor: 'transparent',
                                borderDash: [5, 5],
                                tension: 0.35
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top', labels: { font: { family: 'Manrope' }, color: '#241a1a' } } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { grid: { color: '#e7ddca' } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>