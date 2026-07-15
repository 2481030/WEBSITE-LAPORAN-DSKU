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
                                <tr><td class="px-6 py-3 font-medium">5</td><td class="px-6 py-3">Junior (Setingkat SMP)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">6</td><td class="px-6 py-3">Earliteen (Remaja)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">7</td><td class="px-6 py-3">Youth (Pemuda)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">8</td><td class="px-6 py-3">Adult (Dewasa)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">9</td><td class="px-6 py-3">Jumlah UKSS (Unit Kecil Sekolah Sabat)</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">10</td><td class="px-6 py-3">Jumlah guru SS di Jemaat</td><td class="px-6 py-2"><input type="number" class="report-input w-full p-2 text-center" value="0"></td></tr>
                                <tr><td class="px-6 py-3 font-medium">11</td><td class="px-6 py-3">Melakukan ibadah SS Anak-anak terpisah dari SS dewasa?</td><td class="px-6 py-2">
                                    <select class="report-input w-full p-2 text-center"><option>Ya</option><option>Tidak</option></select>
                                </td></tr>
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
                                <tr><th class="px-6 py-3 uppercase text-left">Indikator Kinerja Departemen</th><th class="px-6 py-3 w-48 text-center uppercase">Nilai Triwulan</th></tr>
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
            'penatalayanan': ["Jumlah anggota jemaat.", "Jumlah seminar Penatalayanan.", "Jumlah unit pembayar persepuluhan.", "Jumlah anggota jemaat yang menerapkan rencana persembahan gabungan."],
            'rumah-tangga': ["Jumlah Rumah Tangga/Keluarga Advent", "Jumlah keluarga GMAHK yang belajar Alkitab secara regular.", "Jumlah jemaat yang melaksanakan Pekan Doa RT.", "Jumlah baptisan dari Rumah Tangga"],
            'pelayanan-anak': ["Jumlah jemaat yang mempunyai coordinator pelayanan anak-anak.", "Jumlah seluruh anak di jemaat di SSAA", "Jumlah anak yang sudah dibaptis berumur kurang dari 15 tahun."],
            'komunikasi': ["Apakah Poster Mission Reach26 sudah sampai ke Group WA Jemaat ?", "Berapa Banyak Jemaat Yang Sudah Membuat Spanduk Program Mission Reach 2026 ?", "Berapa banyak anggota jemaat yang terlibat dalam penginjilan Digital ?"],
            'berkebutuhan-khusus': ["Cacat melihat/buta (Jumlah Jiwa)", "Cacat mendengar/tuli (Jumlah Jiwa)", "Autis/down syndrome (Jumlah Jiwa)"],
            'bwa': ["Retreat BWA yang terlaksana", "Jumlah Wanita yang hadir pada retreat BWA (SDA)", "Jumlah baptisan KKR BWA"],
            'literatur': ["Jumlah penginjil literatur di jemaat.", "Jumlah khotbah literatur.", "Jumlah anggota berlangganan RTK."],
            'roh-nubuat': ["Khotbah Roh Nubuat yang dibawakan", "Anggota yang memiliki buku-buku Roh Nubuat lengkap"],
            'ndr': ["Jumlah Care group baru.", "Jumlah tamu non SDA yang dibawa hadir ke gereja.", "Jumlah baptisan melalui care group."],
            'pemuda-advent': ["Jumlah klub Pathfinder yang berjalan", "Jumlah Klub Master Guide", "Anggota PA yang aktif mengikuti acara PA pada Sabat sore"],
            'kesehatan': ["Mengadakan seminar Kesehatan (Frekuensi)", "Jumlah gereja yang menyajikan makanan vegetarian saat potluck.", "Jumlah anggota jemaat yang sudah vegetarian."],
            'pendidikan': ["Jumlah guru SDA", "Jumlah siswa SDA", "Jumlah baptisan dari institusi pendidikan"]
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
                            <td class="px-6 py-4 font-medium">${index + 1}. <span class="indikator-nama">${field}</span></td>
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