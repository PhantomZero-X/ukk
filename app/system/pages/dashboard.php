<?php
// =============================================================================
// FILE: dashboard.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/system/pages/dashboard.php
// FUNGSI: Halaman dashboard utama dengan ringkasan data peminjam
//         - Total peminjam
//         - Sebaran data per kota
//         - 5 riwayat terakhir
// SISTEM: Rental Mobil UKK WheelFlow
// DEPENDENSI: api_peminjaman.php untuk mengambil data
// =============================================================================

// Include API untuk fungsi mengambil data
include_once __DIR__ . '/../../server/api/api_peminjaman.php';

// =============================================================================
// AMBIL SEMUA DATA dari file CSV
// Fungsi: ambilSemuaData() dari api_peminjaman.php
// Return: Array data peminjam, diurutkan dari terbaru
// =============================================================================
$allData = ambilSemuaData();

// =============================================================================
// HITUNG STATISTIK - Total peminjam
// count(): menghitung jumlah elemen dalam array
// =============================================================================
$totalPeminjam = count($allData);

// =============================================================================
// HITUNG STATISTIK - Persebaran data per kota
// Loop menghitung berapa peminjam dari masing-masing kota
// =============================================================================
$kotaCount = [];  // Array asosiatif: kota => jumlah
foreach($allData as $row) {
    // Jika kota sudah ada, tambah 1. Jika belum, mulai dari 0 + 1
    $kotaCount[$row['kota']] = ($kotaCount[$row['kota']] ?? 0) + 1;
}
?>

<!-- ==========================================================================
     JUDUL HALAMAN
     ========================================================================== -->
<h2 class="text-3xl font-semibold mb-6">Dashboard Summary</h2>

<!-- ==========================================================================
     KARTU STATISTIK - 2 Kartu utama
     --------------------------------------------------------------------------
     1. Total Peminjam - Jumlah keseluruhan data
     2. Sebaran Kota - Breakdown per kota
     ========================================================================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    
    <!-- [KARTU 1] Total Peminjam -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Total Peminjam</p>
        <!-- Menampilkan total dengan font monospace -->
        <p class="text-4xl font-mono"><?= $totalPeminjam ?></p>
    </div>
    
    <!-- [KARTU 2] Sebaran Kota -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Sebaran Kota</p>
        <div class="mt-2 text-sm">
            <!-- Loop menampilkan setiap kota dan jumlahnya -->
            <?php foreach($kotaCount as $kota => $jml): ?>
                <span class="inline-block bg-gray-100 rounded-full px-3 py-1 mr-2 mb-2">
                    <?= $kota ?>: <?= $jml ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==========================================================================
     TABEL 5 RIWAYAT TERAKHIR
     --------------------------------------------------------------------------
     Mengambil 5 data teratas dari array (sudah diurutkan dari terbaru di API)
     Fungsi: array_slice() - mengambil sebagian array
     Parameter: array, offset (mulai dari 0), length (ambil 5)
     ========================================================================== -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-4 border-b">
        <h3 class="font-bold">5 Riwayat Terakhir</h3>
    </div>
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-4">Nama</th>
                <th class="p-4">Kota</th>
                <th class="p-4">Tanggal Register</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Ambil 5 data pertama (terbaru) dari array
            $limit = array_slice($allData, 0, 5);
            foreach($limit as $item): 
            ?>
            <tr class="border-b hover:bg-gray-50">
                <!-- htmlspecialchars(): mencegah XSS attack dengan escape HTML -->
                <td class="p-4"><?= htmlspecialchars($item['nama']) ?></td>
                <td class="p-4"><?= htmlspecialchars($item['kota']) ?></td>
                <td class="p-4 text-sm text-gray-500"><?= $item['tanggal'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ==========================================================================
     END OF FILE: dashboard.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/pages/dashboard.php
     Fungsi: Halaman Dashboard Summary
     ========================================================================== -->
