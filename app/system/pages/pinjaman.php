<?php
// =============================================================================
// FILE: pinjaman.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/system/pages/pinjaman.php
// FUNGSI: Halaman form untuk menambahkan data peminjam baru (CREATE)
// SISTEM: Rental Mobil UKK WheelFlow
// METHOD: POST ke api_peminjaman.php via fungsi simpanDataPeminjam()
// =============================================================================

// Include API untuk fungsi penyimpanan data
// Lokasi API: /opt/lampp/htdocs/RentCar/app/server/api/api_peminjaman.php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';

// Inisialisasi variabel status untuk menampilkan notifikasi
$status = "";

// =============================================================================
// HANDLER: Proses FORM SUBMIT (CREATE Data)
// Method: POST
// Trigger: User klik tombol "Simpan Data"
// =============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan bersihkan spasi di awal/akhir dengan trim()
    $nama = trim($_POST['nama']);         // Nama lengkap peminjam
    $nik = trim($_POST['nik']);           // Nomor Induk Kependudukan (unique ID)
    $alamat = trim($_POST['alamat']);     // Alamat lengkap
    $kota = $_POST['kota'];               // Kota dari dropdown select
    $hp = trim($_POST['hp']);             // Nomor handphone
    $pekerjaan = trim($_POST['pekerjaan']); // Pekerjaan peminjam

    // ========================================================================
    // VALIDASI INPUT - Cek apakah data valid sebelum disimpan
    // ========================================================================
    
    // [VALIDASI 1] Cek field wajib tidak boleh kosong
    if (empty($nama) || empty($nik) || empty($alamat) || empty($hp)) {
        $status = "error: Semua field wajib diisi.";
    } 
    // [VALIDASI 2] Cek NIK hanya boleh angka (tidak boleh huruf)
    elseif (!ctype_digit($nik)) {
        $status = "error: NIK hanya boleh berisi angka 0-9, tidak boleh ada huruf atau spasi.";
    }
    // [VALIDASI 3] Cek NIK harus EXACT 16 digit (tidak lebih, tidak kurang)
    elseif (strlen($nik) !== 16) {
        $status = "error: NIK harus tepat 16 digit angka (tidak boleh lebih atau kurang).";
    }
    // [VALIDASI 4] Cek No HP hanya boleh angka
    elseif (!ctype_digit($hp)) {
        $status = "error: No. HP hanya boleh berisi angka 0-9, tidak boleh ada huruf.";
    }
    // [VALIDASI 5] Cek No HP harus 10-12 digit
    elseif (strlen($hp) < 10 || strlen($hp) > 12) {
        $status = "error: No. HP harus 10-12 digit angka.";
    }
    // [VALIDASI 6] Cek duplikat - NIK, No HP, atau Nama sudah terdaftar
    else {
        $duplikat = cekDuplikatData($nik, $hp, $nama);
        if ($duplikat !== null) {
            // Gunakan status 'failed' karena ini bukan error program, tapi data sudah ada
            $status = "failed: " . $duplikat['field'] . " '" . $duplikat['value'] . "' sudah terdaftar. Gunakan data yang berbeda.";
        } else {
            // [STEP SIMPAN] Panggil fungsi dari API untuk menyimpan data
            $result = simpanDataPeminjam([$nama, $nik, $alamat, $kota, $hp, $pekerjaan]);
            
            if ($result === true) {
                $status = "success: Data berhasil disimpan!";
            } else {
                $status = "error: " . $result;
            }
        }
    }
}
?>

<!-- ==========================================================================
     FORM TAMBAH DATA PEMINJAM
     --------------------------------------------------------------------------
     Method: POST
     Action: ke halaman ini sendiri (self-submit)
     Styling: TailwindCSS dengan utility classes
     ========================================================================== -->
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold mb-6">Tambah Data Peminjam</h2>
    
    <!-- ========================================================================
         NOTIFIKASI STATUS - Muncul setelah submit
         Kategori:
         - success : Data tersimpan (hijau)
         - failed  : Duplikat data (kuning/oranye)
         - error   : Format salah/bug (merah)
         - warning : Peringatan (kuning)
         - danger  : Error fatal (merah tua)
         ======================================================================== -->
    <?php if($status): 
        // Tentukan styling berdasarkan kategori status
        $statusLower = strtolower($status);
        if (strpos($statusLower, 'success') === 0) {
            $notifClass = 'bg-green-100 text-green-700 border border-green-300';
            $notifIcon = '✓';
        } elseif (strpos($statusLower, 'failed') === 0) {
            $notifClass = 'bg-orange-100 text-orange-700 border border-orange-300';
            $notifIcon = '⚠';
        } elseif (strpos($statusLower, 'error') === 0) {
            $notifClass = 'bg-red-100 text-red-700 border border-red-300';
            $notifIcon = '✗';
        } elseif (strpos($statusLower, 'warning') === 0) {
            $notifClass = 'bg-yellow-100 text-yellow-700 border border-yellow-300';
            $notifIcon = '⚡';
        } elseif (strpos($statusLower, 'danger') === 0) {
            $notifClass = 'bg-red-200 text-red-800 border border-red-400';
            $notifIcon = '☠';
        } else {
            $notifClass = 'bg-slate-100 text-slate-700 border border-slate-300';
            $notifIcon = '•';
        }
        // Hapus prefix status dari pesan yang ditampilkan
        $displayMessage = preg_replace('/^(success|failed|error|warning|danger):\s*/i', '', $status);
    ?>
        <div class="mb-4 p-3 rounded <?= $notifClass ?>">
            <span class="font-bold mr-2"><?= $notifIcon ?></span><?= $displayMessage ?>
        </div>
    <?php endif; ?>

    <!-- ========================================================================
         FORM INPUT - 6 Field Data Peminjam
         ======================================================================== -->
    <form action="" method="POST" class="space-y-4">
        
        <!-- [FIELD 1] Nama Lengkap - type text -->
        <div>
            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        
        <!-- [FIELD 2] NIK - type text dengan validasi min 16 karakter -->
        <div>
            <label class="block text-sm font-medium mb-1">NIK (16 Karakter)</label>
            <input type="text" name="nik" value="<?= isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : '' ?>" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        
        <!-- [FIELD 3] Alamat - type textarea (multi-line input) -->
        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded h-24 focus:ring-2 focus:ring-blue-400 outline-none" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
        </div>
        
        <!-- [FIELD 4 & 5] Kota & No.HP - 2 kolom dalam 1 baris -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kota</label>
                <!-- Select dropdown untuk pilihan kota -->
                <?php $selectedKota = isset($_POST['kota']) ? $_POST['kota'] : 'Jakarta'; ?>
                <select name="kota" class="w-full border p-2 rounded outline-none">
                    <option <?= $selectedKota === 'Jakarta' ? 'selected' : '' ?>>Jakarta</option>
                    <option <?= $selectedKota === 'Bandung' ? 'selected' : '' ?>>Bandung</option>
                    <option <?= $selectedKota === 'Surabaya' ? 'selected' : '' ?>>Surabaya</option>
                    <option <?= $selectedKota === 'Yogyakarta' ? 'selected' : '' ?>>Yogyakarta</option>
                    <option <?= $selectedKota === 'Medan' ? 'selected' : '' ?>>Medan</option>
                    <option <?= $selectedKota === 'Pasuruan' ? 'selected' : '' ?>>Pasuruan</option>
                    <option <?= $selectedKota === 'Pandaan' ? 'selected' : '' ?>>Pandaan</option>
                    <option <?= $selectedKota === 'Malang' ? 'selected' : '' ?>>Malang</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">No. HP</label>
                <input type="text" name="hp" value="<?= isset($_POST['hp']) ? htmlspecialchars($_POST['hp']) : '' ?>" class="w-full border p-2 rounded outline-none" required>
            </div>
        </div>
        
        <!-- [FIELD 6] Pekerjaan - type text -->
        <div>
            <label class="block text-sm font-medium mb-1">Pekerjaan</label>
            <input type="text" name="pekerjaan" value="<?= isset($_POST['pekerjaan']) ? htmlspecialchars($_POST['pekerjaan']) : '' ?>" class="w-full border p-2 rounded outline-none" required>
        </div>
        
        <!-- [TOMBOL SUBMIT] Simpan Data -->
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Simpan Data</button>
    </form>
</div>

<!-- ==========================================================================
     END OF FILE: pinjaman.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/pages/pinjaman.php
     Fungsi: Halaman Create Data Peminjam (Tambah Pinjaman)
     ========================================================================== -->
