<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$allData = ambilSemuaData();
$totalPeminjam = count($allData);

// Logika hitung per kota
$kotaCount = [];
foreach($allData as $row) {
    $kotaCount[$row['kota']] = ($kotaCount[$row['kota']] ?? 0) + 1;
}
?>

<h2 class="text-3xl font-semibold mb-6">Dashboard Summary</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Total Peminjam</p>
        <p class="text-4xl font-mono"><?= $totalPeminjam ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Sebaran Kota</p>
        <div class="mt-2 text-sm">
            <?php foreach($kotaCount as $kota => $jml): ?>
                <span class="inline-block bg-gray-100 rounded-full px-3 py-1 mr-2 mb-2"><?= $kota ?>: <?= $jml ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

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
            $limit = array_slice($allData, 0, 5);
            foreach($limit as $item): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4"><?= htmlspecialchars($item['nama']) ?></td>
                <td class="p-4"><?= htmlspecialchars($item['kota']) ?></td>
                <td class="p-4 text-sm text-gray-500"><?= $item['tanggal'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>