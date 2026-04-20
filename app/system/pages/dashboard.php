<?php
// =============================================================================
// FILE: dashboard.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/system/pages/dashboard.php
// FUNGSI: Halaman dashboard utama yang menampilkan ringkasan data peminjam
//         - Total peminjam
//         - Statistik peminjam per kota
//         - 5 aktivitas terbaru (data peminjam terbaru)
// SISTEM: Rental Mobil UKK WheelFlow
// DEPENDENSI: api_peminjaman.php, DataTables CSS, TailwindCSS
// =============================================================================

// Include API untuk fungsi mengambil data
// Lokasi API: /opt/lampp/htdocs/RentCar/app/server/api/api_peminjaman.php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';

// Ambil semua data
$allData = ambilSemuaData();
$totalPeminjam = count($allData);

// Hitung Statistik per Kota
$kotaCount = [];
foreach($allData as $row) {
    $kotaCount[$row['kota']] = ($kotaCount[$row['kota']] ?? 0) + 1;
}

// Ambil 5 data terbaru untuk tabel dashboard
$limitData = array_slice($allData, 0, 5);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Styling agar seragam dengan history.php (Siku Tajam) */
    .dataTables_wrapper .dataTables_filter { margin-bottom: 15px; }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0px !important;
        padding: 4px 8px !important;
        outline: none;
    }
    /* Sembunyikan pagination jika data hanya 5 (agar lebih clean) */
    .dataTables_wrapper .dataTables_paginate { display: none; }
    .dataTables_wrapper .dataTables_info { display: none; }
    .dataTables_wrapper .dataTables_length { display: none; }
</style>

<!-- ==========================================================================
     HEADER HALAMAN DASHBOARD
     ========================================================================== -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h2>
    <p class="text-slate-500 text-sm">Ringkasan aktivitas sistem peminjaman mobil hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    
    <div class="bg-white p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Peminjam</p>
        <h3 class="text-4xl font-extrabold text-slate-900"><?= number_format($totalPeminjam) ?></h3>
        <p class="text-[10px] text-blue-600 font-bold mt-4 uppercase tracking-tighter italic">Berdasarkan Database CSV</p>
    </div>

    <div class="md:col-span-2 bg-white p-6 border border-slate-200 shadow-sm">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Sebaran Peminjam Berdasarkan Kota</p>
        <div class="flex flex-wrap gap-3">
            <?php if(empty($kotaCount)): ?>
                <p class="text-sm text-slate-400 italic">Belum ada data kota...</p>
            <?php else: ?>
                <?php foreach($kotaCount as $kota => $jml): ?>
                <div class="flex items-center bg-slate-50 border border-slate-100 px-4 py-2">
                    <span class="text-sm font-semibold text-slate-700 mr-3"><?= htmlspecialchars($kota) ?></span>
                    <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-none"><?= $jml ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <div>
            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Data Pinjaman</h3>
            <p class="text-[10px] text-slate-400 uppercase">Data peminjam mobil terdaftar dalam sistem</p>
        </div>
        <a href="?page=history" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-widest">Lihat Semua &rarr;</a>
    </div>
    
    <div class="p-6">
        <table id="tabelDashboard" class="w-full text-left cell-border stripe">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="p-3 text-xs uppercase font-bold text-center">No</th>
                    <th class="p-3 text-xs uppercase font-bold">Nama</th>
                    <th class="p-3 text-xs uppercase font-bold text-center">Asal Kota</th>
                    <th class="p-3 text-xs uppercase font-bold">Register</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($limitData)): ?>
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400 text-sm italic">Belum ada aktivitas peminjaman.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($limitData as $item): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-3 text-center text-sm font-bold text-gray-700"><?= $no++ ?></td>
                        <td class="p-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($item['nama']) ?></td>
                        <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['kota']) ?></td>
                        <td class="p-3 text-xs text-gray-400 font-mono"><?= $item['tanggal'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelDashboard').DataTable({
            "searching": false, // Karena hanya 5 data, search box tidak terlalu diperlukan di dashboard
            "paging": false,
            "info": false,
            "order": [[2, "desc"]], // Urutkan berdasarkan tanggal register (kolom ke-3)
            "language": {
                "emptyTable": "Tidak ada data tersedia"
            }
        });
    });
</script>

<!-- ==========================================================================
     END OF FILE: dashboard.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/pages/dashboard.php
     Fungsi: Halaman Dashboard dengan ringkasan data peminjam
     ========================================================================== -->