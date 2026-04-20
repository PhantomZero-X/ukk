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
    // [VALIDASI 2] Cek NIK harus angka dan minimal 16 karakter
    // strlen(): menghitung panjang string
    // is_numeric(): cek apakah value berupa angka
    elseif (strlen($nik) < 16 || !is_numeric($nik)) {
        $status = "error: NIK harus angka dan minimal 16 karakter.";
    } else {
        // [STEP SIMPAN] Panggil fungsi dari API untuk menyimpan data
        // Data dikirim sebagai array terurut: [nama, nik, alamat, kota, hp, pekerjaan]
        $result = simpanDataPeminjam([$nama, $nik, $alamat, $kota, $hp, $pekerjaan]);
        
        if ($result === true) {
            $status = "success: Data berhasil disimpan!";
        } else {
            // Jika gagal, tampilkan pesan error dari API (misal: permission error)
            $status = "error: " . $result;
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
<div class="max-w-2xl bg-white p-8 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold mb-6">Tambah Data Peminjam</h2>
    
    <!-- ========================================================================
         NOTIFIKASI STATUS - Muncul setelah submit
         Styling: Hijau untuk success, Merah untuk error
         ======================================================================== -->
    <?php if($status): ?>
        <div class="mb-4 p-3 rounded <?= strpos($status, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= $status ?>
        </div>
    <?php endif; ?>

    <!-- ========================================================================
         FORM INPUT - 6 Field Data Peminjam
         ======================================================================== -->
    <form action="" method="POST" class="space-y-4">
        
        <!-- [FIELD 1] Nama Lengkap - type text -->
        <div>
            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="nama" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        
        <!-- [FIELD 2] NIK - type text dengan validasi min 16 karakter -->
        <div>
            <label class="block text-sm font-medium mb-1">NIK (16 Karakter)</label>
            <input type="text" name="nik" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        
        <!-- [FIELD 3] Alamat - type textarea (multi-line input) -->
        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded h-24 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
        </div>
        
        <!-- [FIELD 4 & 5] Kota & No.HP - 2 kolom dalam 1 baris -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kota</label>
                <!-- Select dropdown untuk pilihan kota -->
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
        
        <!-- [FIELD 6] Pekerjaan - type text -->
        <div>
            <label class="block text-sm font-medium mb-1">Pekerjaan</label>
            <input type="text" name="pekerjaan" class="w-full border p-2 rounded outline-none" required>
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
