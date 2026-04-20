<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nik = trim($_POST['nik']);
    $alamat = trim($_POST['alamat']);
    $kota = $_POST['kota'];
    $hp = trim($_POST['hp']);
    $pekerjaan = trim($_POST['pekerjaan']);

    // Validasi
    if (empty($nama) || empty($nik) || empty($alamat) || empty($hp)) {
        $status = "error: Semua field wajib diisi.";
    } elseif (strlen($nik) < 16 || !is_numeric($nik)) {
        $status = "error: NIK harus angka dan minimal 16 karakter.";
    } else {
        $result = simpanDataPeminjam([$nama, $nik, $alamat, $kota, $hp, $pekerjaan]);
        if ($result === true) {
            $status = "success: Data berhasil disimpan!";
        } else {
            $status = "error: " . $result;
        }
    }
}
?>

<div class="max-w-2xl bg-white p-8 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold mb-6">Tambah Data Peminjam</h2>
    
    <?php if($status): ?>
        <div class="mb-4 p-3 rounded <?= strpos($status, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= $status ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="nama" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">NIK (Min. 16 Karakter)</label>
            <input type="text" name="nik" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded h-24 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kota</label>
                <select name="kota" class="w-full border p-2 rounded outline-none">
                    <option>Jakarta</option>
                    <option>Bandung</option>
                    <option>Surabaya</option>
                    <option>Yogyakarta</option>
                    <option>Medan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">No. HP</label>
                <input type="text" name="hp" class="w-full border p-2 rounded outline-none" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Pekerjaan</label>
            <input type="text" name="pekerjaan" class="w-full border p-2 rounded outline-none" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Simpan Data</button>
    </form>
</div>