<?php
// =============================================================================
// FILE: main.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/system/layout/main.php
// FUNGSI: Layout utama (Master Template) aplikasi Rental Mobil
//         - Menyusun struktur HTML dasar aplikasi
//         - Menampilkan sidebar navigasi
//         - Menampilkan header dan footer
//         - Meload konten halaman dinamis dari folder pages/
//         - Handler export CSV/TXT (harus sebelum output HTML!)
// SISTEM: Rental Mobil UKK WheelFlow
// DEPENDENSI: TailwindCSS CDN, Google Fonts (Inter), Lucide Icons
// =============================================================================

// ==========================================================================
// TOMBOL EXPORT CSV & TXT
// Fungsi: Mengarahkan ke handler export di main.php dengan parameter action
// URL: ?page=history&action=export_csv atau ?page=history&action=export_txt
// Styling: TailwindCSS dengan icon SVG
// ==========================================================================

// =============================================================================
// HANDLER: Proses EXPORT CSV dan TXT
// Method: GET dengan parameter page=history&action=export_csv/export_txt
// Lokasi: Paling atas file (sebelum <!DOCTYPE html>)
// Fungsi: Download data peminjam sebagai file CSV atau TXT
// Alasan: Header download harus dikirim sebelum output HTML apapun
// =============================================================================
if (isset($_GET['page']) && $_GET['page'] === 'history' && isset($_GET['action'])) {
    include_once __DIR__ . '/../../server/api/api_peminjaman.php';
    
    // Export CSV
    if ($_GET['action'] === 'export_csv') {
        $data = ambilSemuaData();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="data_peminjam_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
        fputcsv($output, ['No', 'Nama Lengkap', 'NIK', 'Alamat', 'Kota', 'No. HP', 'Pekerjaan', 'Tanggal Register']);
        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [$no++, $row['nama'], $row['nik'], $row['alamat'], $row['kota'], $row['hp'], $row['pekerjaan'], $row['tanggal']]);
        }
        fclose($output);
        exit;
    }
    
    // Export TXT
    if ($_GET['action'] === 'export_txt') {
        $data = ambilSemuaData();
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="data_peminjam_' . date('Y-m-d') . '.txt"');
        $content = "================================================================================\n";
        $content .= "                         DATA PEMINJAM RENTAL MOBIL\n";
        $content .= "                         Export Tanggal: " . date('d/m/Y H:i:s') . "\n";
        $content .= "================================================================================\n\n";
        $no = 1;
        foreach ($data as $row) {
            $content .= "--------------------------------------------------------------------------------\n";
            $content .= "No.         : " . $no++ . "\n";
            $content .= "Nama        : " . $row['nama'] . "\n";
            $content .= "NIK         : " . $row['nik'] . "\n";
            $content .= "Alamat      : " . $row['alamat'] . "\n";
            $content .= "Kota        : " . $row['kota'] . "\n";
            $content .= "No. HP      : " . $row['hp'] . "\n";
            $content .= "Pekerjaan   : " . $row['pekerjaan'] . "\n";
            $content .= "Tgl Register: " . $row['tanggal'] . "\n";
            $content .= "--------------------------------------------------------------------------------\n\n";
        }
        $content .= "================================================================================\n";
        $content .= "Total Data: " . count($data) . " peminjam\n";
        $content .= "================================================================================\n";
        echo $content;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="WheelFlow - Sistem Manajemen Rental Mobil Efisien dan Modern">
    <meta name="author" content="KomiDev">
    <title>WheelFlow | Solusi Rental Mobil Modern</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link-active { @apply bg-slate-800 text-blue-400 border-l-4 border-blue-500; }
        html { scroll-behavior: smooth; }
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Animasi fade-in halaman */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page { animation: fadeIn 0.4s ease-out; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased">
    
    <div class="flex min-h-screen relative">
        
        <aside class="hidden md:flex w-72 bg-slate-900 flex-col sticky top-0 h-screen shadow-xl z-20">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-none flex items-center justify-center shadow-lg shadow-blue-900/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-terminal w-6 h-6 text-white">
                            <path d="M12 19h8"></path>
                            <path d="m4 17 6-6-6-6"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-white">
                        Komi<span class="text-blue-500">Dev</span>
                    </h1>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <?php
                    $current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                    // Menu navigasi tanpa icon (hanya teks untuk menghindari pertanyaan tidak logis)
                    $menus = [
                        ['id' => 'dashboard', 'label' => 'Dashboard'],
                        ['id' => 'pinjaman', 'label' => 'Tambah Pinjaman'],
                        ['id' => 'history', 'label' => 'Riwayat Data'],
                    ];

                    foreach ($menus as $menu):
                        $isActive = ($current_page == $menu['id']);
                        $activeClass = $isActive ? 'bg-slate-800 text-blue-400 border-l-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-l-4 border-transparent';
                ?>
                <!-- Menu item tanpa icon -->
                <a href="?page=<?= $menu['id'] ?>" class="block px-4 py-3 text-sm font-medium transition-all <?= $activeClass ?>">
                    <?= $menu['label'] ?>
                </a>
                <?php endforeach; ?>
            </nav>

        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            
            <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-10 flex items-center justify-between px-4 md:px-8">
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md hover:bg-slate-100 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="hidden md:block">
                    <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Sistem Informasi Rental Mobil</h2>
                </div>

                <div class="flex items-center gap-4 text-sm text-slate-500 font-medium">
                    <span class="hidden sm:inline-block bg-slate-50 px-3 py-1 rounded-none border border-slate-200 text-[11px] font-bold"><?= date('l, d F Y') ?></span>
                </div>
            </header>

            <main class="p-4 md:p-8 flex-1">
                <div class="max-w-6xl mx-auto animate-page">
                    <?php 
                        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                        $file = __DIR__ . "/../pages/$page.php";
                        if(file_exists($file)){
                            include $file;
                        } else {
                            include __DIR__ . "/../pages/dashboard.php";
                        }
                    ?>
                </div>
            </main>

            <footer class="py-6 px-8 border-t border-slate-100 bg-white">
                <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider">&copy; 2026 <span class="text-slate-900 font-bold">Komi<span class="text-blue-600">Dev</span></span>. Built for efficiency.</p>
                </div>
            </footer>
        </div>
    </div>

    <div id="mobileSidebar" class="fixed inset-0 bg-slate-900/60 z-50 hidden backdrop-blur-sm transition-all">
        <div class="w-72 bg-slate-900 h-full p-6 shadow-2xl animate-[slideIn_0.3s_ease-out]">
            <div class="flex justify-between items-center mb-10 border-b border-slate-800 pb-6">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-terminal w-6 h-6 text-blue-500">
                        <path d="M12 19h8"></path>
                        <path d="m4 17 6-6-6-6"></path>
                    </svg>
                    <h1 class="text-xl font-bold text-white">Komi<span class="text-blue-500">Dev</span></h1>
                </div>
                <button id="closeMenuBtn" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>
            <nav class="space-y-2">
                <a href="?page=dashboard" class="block text-slate-300 py-3 px-4 hover:bg-slate-800 transition">Dashboard</a>
                <a href="?page=pinjaman" class="block text-slate-300 py-3 px-4 hover:bg-slate-800 transition">Tambah Pinjaman</a>
                <a href="?page=history" class="block text-slate-300 py-3 px-4 hover:bg-slate-800 transition">Riwayat Data</a>
            </nav>
        </div>
    </div>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');

        mobileMenuBtn.addEventListener('click', () => mobileSidebar.classList.remove('hidden'));
        closeMenuBtn.addEventListener('click', () => mobileSidebar.classList.add('hidden'));
        
        mobileSidebar.addEventListener('click', (e) => {
            if(e.target === mobileSidebar) mobileSidebar.classList.add('hidden');
        });

        const keyframes = document.createElement('style');
        keyframes.innerHTML = `
            @keyframes slideIn {
                from { transform: translateX(-100%); }
                to { transform: translateX(0); }
            }
        `;
        document.head.appendChild(keyframes);
    </script>

<!-- ==========================================================================
     END OF FILE: main.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/layout/main.php
     Fungsi: Layout utama aplikasi dengan handler export
     ========================================================================== -->
</body>
</html>