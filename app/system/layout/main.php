<!DOCTYPE html>
<!-- =============================================================================
     FILE: main.php
     LOKASI: /opt/lampp/htdocs/RentCar/app/system/layout/main.php
     FUNGSI: Layout utama/template sistem (Master Layout)
             - Sidebar navigasi
             - Content area untuk halaman
             - Styling dengan TailwindCSS
     SISTEM: Rental Mobil UKK WheelFlow
     ROLE: Template wrapper, include halaman dinamis dari /pages/
     ============================================================================= -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Viewport untuk responsive design (mobile-friendly) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WheelFlow - Rental System</title>
    
    <!-- TAILWIND CSS - CSS Framework via CDN -->
    <!-- Source: https://cdn.tailwindcss.com -->
    <!-- Fungsi: Utility-first CSS framework untuk styling cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- Body dengan background gray-50 (abu-abu muda) dan text gray-800 (hitam keabu-abuan) -->
<body class="bg-gray-50 text-gray-800">
    
    <!-- ==========================================================================
         LAYOUT UTAMA - Flexbox container
         Struktur: Sidebar (kiri) + Content (kanan)
         ========================================================================== -->
    <div class="flex min-h-screen">
        
        <!-- ========================================================================
             SIDEBAR NAVIGATION - Panel navigasi di sisi kiri
             Styling: w-64 (width 16rem/256px), bg-slate-900 (warna gelap), text putih
             Responsive: hidden md:block (sembunyi di mobile, tampil di medium screen)
             ======================================================================== -->
        <aside class="w-64 bg-slate-900 text-white p-6 hidden md:block">
            
            <!-- [LOGO/BRAND] Nama aplikasi -->
            <h1 class="text-2xl font-bold mb-10 text-blue-400">KomiDev</h1>
            
            <!-- [NAVIGATION MENU] Link ke halaman-halaman -->
            <nav class="space-y-4">
                
                <!-- Link Dashboard - parameter ?page=dashboard -->
                <a href="?page=dashboard" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">
                    Dashboard
                </a>
                
                <!-- Link Tambah Pinjaman - parameter ?page=pinjaman -->
                <a href="?page=pinjaman" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">
                    Tambah Pinjaman
                </a>
                
                <!-- Link Riwayat Data - parameter ?page=history -->
                <a href="?page=history" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">
                    Riwayat Data
                </a>
            </nav>
        </aside>

        <!-- ========================================================================
             MAIN CONTENT AREA - Area konten dinamis
             flex-1: mengisi sisa ruang yang tersedia
             p-8: padding 2rem/32px di semua sisi
             ======================================================================== -->
        <main class="flex-1 p-8">
            <?php 
                // =================================================================
                // DYNAMIC PAGE LOADER - Memuat halaman berdasarkan parameter URL
                // Parameter: $_GET['page'] dari URL (contoh: ?page=dashboard)
                // Default: 'dashboard' jika parameter tidak ada
                // Security: include file dari folder ../pages/ (whitelist)
                // =================================================================
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                
                // Include file halaman dari folder pages
                // __DIR__ = directory file ini berada (/app/system/layout/)
                // Path: /app/system/pages/$page.php
                include __DIR__ . "/../pages/$page.php"; 
            ?>
        </main>
    </div>
</body>
</html>

<!-- =============================================================================
     END OF FILE: main.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/layout/main.php
     Fungsi: Master Layout Template
     ========================================================================== -->
