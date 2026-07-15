<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan Triwulan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-link.active {
            background-color: #1e3a8a;
            color: #ffffff;
            border-left: 4px solid #3b82f6;
        }
        .dept-content { display: none; }
        .dept-content.active { display: block; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <div class="w-64 bg-slate-900 text-slate-300 flex flex-col h-full shadow-xl z-20">
            <div class="p-5 bg-slate-950 flex flex-col items-center border-b border-slate-800">
                <span class="text-xl font-bold text-white tracking-wider text-center">DSKU REPORT</span>
                <span class="text-xs text-blue-400 mt-1 text-center">Sumatera Kawasan Utara</span>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4 space-y-1" id="sidebar-menu">
                <a href="#" onclick="switchTab('ringkasan')" class="sidebar-link active flex items-center px-6 py-3 text-sm font-medium hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-chart-pie w-5 mr-3 text-lg text-blue-500"></i> Ringkasan Utama
                </a>
                <div class="px-6 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Departemen</div>
                
                <a href="#" onclick="switchTab('sekolah-sabat')" class="sidebar-link flex items-center px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-book-open w-5 mr-3 text-slate-400"></i> Sekolah Sabat
                </a>
                <a href="#" onclick="switchTab('penginjilan-perorangan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-users-rays w-5 mr-3 text-slate-400"></i> Penginjilan Perorangan
                </a>
                <a href="#" onclick="switchTab('penatalayanan')" class="sidebar-link flex items-center px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-hand-holding-dollar w-5 mr-3 text-slate-400"></i> Penatalayanan
                </a>
            </div>
        </div>

        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Laporan Triwulan Pelayanan Jemaat</h1>
                    <p class="text-xs text-gray-500">Daerah Sumatera Kawasan Utara</p>
                </div>
            </header>

            <main class="p-8 max-w-7xl w-full mx-auto flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>