<?php
// =============================================================================
// FILE: history.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/system/pages/history.php
// FUNGSI: Halaman riwayat data peminjam dengan fitur CRUD lengkap
//         (Create, Read, Update, Delete) via DataTables dan Modal
// SISTEM: Rental Mobil UKK WheelFlow
// DEPENDENSI: api_peminjaman.php, DataTables jQuery Plugin, TailwindCSS
// =============================================================================

// Include API untuk operasi database/file
include_once __DIR__ . '/../../server/api/api_peminjaman.php';

// =============================================================================
// HANDLER: Proses DELETE (Hapus Data)
// Method: GET dengan parameter action=delete&nik=xxx
// Lokasi: Bagian atas file (sebelum output HTML)
// =============================================================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nik'])) {
    $nik_delete = $_GET['nik'];
    $delete_result = hapusDataPeminjam($nik_delete);
    
    // Redirect kembali ke history dengan status
    if ($delete_result) {
        header("Location: ?page=history&status=deleted");
    } else {
        header("Location: ?page=history&status=error");
    }
    exit;
}

// =============================================================================
// HANDLER: Proses UPDATE (Edit Data)
// Method: POST dengan parameter action=update
// Lokasi: Bagian atas file (sebelum output HTML)
// =============================================================================
$update_status = "";
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $nik_lama = $_POST['nik_lama'];           // NIK asli sebagai identifier
    $data_baru = [
        $_POST['nama'],
        $_POST['nik'],                        // NIK bisa berubah
        $_POST['alamat'],
        $_POST['kota'],
        $_POST['hp'],
        $_POST['pekerjaan']
    ];
    
    $update_result = updateDataPeminjam($nik_lama, $data_baru);
    if ($update_result) {
        $update_status = "success_update";
    } else {
        $update_status = "error_update";
    }
}

// =============================================================================
// AMBIL SEMUA DATA untuk ditampilkan di tabel
// Fungsi: ambilSemuaData() dari api_peminjaman.php
// Return: Array data peminjam diurutkan dari terbaru
// =============================================================================
$allData = ambilSemuaData();
?>

<!-- ==========================================================================
     CSS DEPENDENCIES
     --------------------------------------------------------------------------
     1. DataTables CSS - Library untuk tabel interaktif (sorting, search, pagination)
        Source: https://cdn.datatables.net
     2. Custom CSS - Override styling DataTables agar sesuai dengan Tailwind
     ========================================================================== -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    /* ========================================================================
       CUSTOM STYLING: DataTables Override
       Tujuan: Merapikan tampilan DataTables agar sesuai dengan design Tailwind
       Lokasi: /opt/lampp/htdocs/RentCar/app/system/pages/history.php (bagian <style>)
       ======================================================================== */
    
    /* [1] Jarak antara search bar dengan tabel */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 20px;
    }

    /* [2] Styling input search - tajam tanpa border-radius */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db !important;
        border-radius: 0px !important;
        padding: 4px 10px !important;
        margin-left: 10px !important;
        font-size: 0.875rem;
        outline: none;
    }

    /* [3] Styling tombol pagination (Next/Previous) */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 2px 10px !important;
        font-size: 0.75rem !important;
        border-radius: 0px !important;
    }

    /* [4] Jarak antara tabel dengan pagination di bawah */
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 20px !important;
        font-size: 0.875rem;
    }

    /* [5] Header tabel styling */
    table.dataTable thead th {
        border-bottom: 1px solid #1e293b !important;
    }

    /* [6] MODAL STYLING - Background overlay gelap */
    .modal {
        display: none;              /* Hidden by default, ditampilkan via JavaScript */
        position: fixed;            /* Tetap di posisi layar meski scroll */
        z-index: 1000;              /* Di atas semua elemen lain */
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5); /* Overlay hitam transparan */
        overflow: auto;
    }

    /* [7] MODAL CONTENT - Box putih di tengah */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;            /* Center horizontal, 5% dari atas */
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px;         /* Maksimal lebar 600px */
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* [8] MODAL HEADER */
    .modal-header {
        background: #1e293b;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* [9] TOMBOL CLOSE MODAL */
    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: #ccc;
    }

    /* [10] MODAL BODY */
    .modal-body {
        padding: 20px;
    }

    /* [11] GRID untuk detail view */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 10px;
        margin-bottom: 10px;
    }
    .detail-label {
        font-weight: bold;
        color: #64748b;
    }
</style>

<!-- ==========================================================================
     HEADER HALAMAN
     ========================================================================== -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold text-slate-800">Riwayat Peminjaman</h2>
    <div class="text-xs text-gray-500 font-mono">Storage: CSV & JSON</div>
</div>

<!-- ==========================================================================
     NOTIFIKASI STATUS - Muncul setelah operasi CRUD
     ========================================================================== -->
<?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-300">
        ✓ Data berhasil dihapus!
    </div>
<?php elseif ($update_status === "success_update"): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-300">
        ✓ Data berhasil diupdate!
    </div>
<?php elseif ($update_status === "error_update" || (isset($_GET['status']) && $_GET['status'] === 'error')): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-300">
        ✗ Terjadi kesalahan. Silakan coba lagi.
    </div>
<?php endif; ?>

<!-- ==========================================================================
     TABEL DATA - Menggunakan DataTables jQuery
     Struktur: 7 Kolom (No, Nama, NIK, Kota, No.HP, Register, Aksi)
     ========================================================================== -->
<div class="bg-white p-6 shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table id="tabelPeminjam" class="w-full text-left cell-border stripe">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="p-3 text-xs uppercase font-bold text-center">No</th>
                    <th class="p-3 text-xs uppercase font-bold">Nama</th>
                    <th class="p-3 text-xs uppercase font-bold text-center">NIK</th>
                    <th class="p-3 text-xs uppercase font-bold text-center">Kota</th>
                    <th class="p-3 text-xs uppercase font-bold text-center">No. HP</th>
                    <th class="p-3 text-xs uppercase font-bold">Register</th>
                    <th class="p-3 text-xs uppercase font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $no = 1; foreach($allData as $item): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-3 text-center text-sm font-bold text-gray-700"><?= $no++ ?></td>
                    <td class="p-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($item['nama']) ?></td>
                    <td class="p-3 text-sm text-center text-gray-600" data-nik="<?= htmlspecialchars($item['nik']) ?>"><?= htmlspecialchars($item['nik']) ?></td>
                    <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['kota']) ?></td>
                    <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['hp']) ?></td>
                    <td class="p-3 text-xs text-gray-400 font-mono"><?= $item['tanggal'] ?></td>
                    <td class="p-3">
                        <!-- ================================================================
                             ICON AKSI: Read (View), Update (Edit), Delete (Hapus)
                             ================================================================ -->
                        <div class="flex justify-center gap-3">
                            <!-- [READ] Icon View - Membuka modal detail -->
                            <a href="#" onclick="openDetailModal('<?= htmlspecialchars($item['nik']) ?>', '<?= htmlspecialchars($item['nama']) ?>', '<?= htmlspecialchars($item['alamat']) ?>', '<?= htmlspecialchars($item['kota']) ?>', '<?= htmlspecialchars($item['hp']) ?>', '<?= htmlspecialchars($item['pekerjaan']) ?>', '<?= $item['tanggal'] ?>')" class="text-blue-500 hover:text-blue-700" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            
                            <!-- [UPDATE] Icon Edit - Membuka modal form edit -->
                            <a href="#" onclick="openEditModal('<?= htmlspecialchars($item['nik']) ?>', '<?= htmlspecialchars($item['nama']) ?>', '<?= htmlspecialchars($item['alamat']) ?>', '<?= htmlspecialchars($item['kota']) ?>', '<?= htmlspecialchars($item['hp']) ?>', '<?= htmlspecialchars($item['pekerjaan']) ?>')" class="text-emerald-500 hover:text-emerald-700" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            
                            <!-- [DELETE] Icon Delete - Konfirmasi hapus -->
                            <a href="#" onclick="confirmDelete('<?= htmlspecialchars($item['nik']) ?>', '<?= htmlspecialchars($item['nama']) ?>')" class="text-red-500 hover:text-red-700" title="Hapus Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==========================================================================
     MODAL DETAIL (READ) - Menampilkan informasi lengkap satu peminjam
     Trigger: Icon View (mata) di tabel
     Fungsi: Menampilkan detail lengkap tanpa bisa edit
     ========================================================================== -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">Detail Data Peminjam</h3>
            <span class="close" onclick="closeModal('detailModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-label">Nama Lengkap:</div>
                <div id="detail-nama"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">NIK:</div>
                <div id="detail-nik"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Alamat:</div>
                <div id="detail-alamat"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Kota:</div>
                <div id="detail-kota"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">No. HP:</div>
                <div id="detail-hp"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Pekerjaan:</div>
                <div id="detail-pekerjaan"></div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Tanggal Register:</div>
                <div id="detail-tanggal"></div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL EDIT (UPDATE) - Form untuk mengedit data peminjam
     Trigger: Icon Edit (pensil) di tabel
     Method: POST ke halaman ini sendiri dengan action=update
     ========================================================================== -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">Edit Data Peminjam</h3>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form action="" method="POST">
                <!-- Hidden field untuk menyimpan NIK asli sebagai identifier -->
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="nik_lama" id="edit-nik-lama">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit-nama" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">NIK</label>
                    <input type="text" name="nik" id="edit-nik" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Alamat</label>
                    <textarea name="alamat" id="edit-alamat" class="w-full border border-gray-300 p-2 rounded h-20" required></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Kota</label>
                        <select name="kota" id="edit-kota" class="w-full border border-gray-300 p-2 rounded">
                            <option>Jakarta</option>
                            <option>Bandung</option>
                            <option>Surabaya</option>
                            <option>Yogyakarta</option>
                            <option>Medan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">No. HP</label>
                        <input type="text" name="hp" id="edit-hp" class="w-full border border-gray-300 p-2 rounded" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Pekerjaan</label>
                    <input type="text" name="pekerjaan" id="edit-pekerjaan" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL DELETE CONFIRMATION - Konfirmasi penghapusan data
     Trigger: Icon Delete (sampah) di tabel
     Fungsi: Mencegah hapus data tidak sengaja (accidental delete)
     ========================================================================== -->
<div id="deleteModal" class="modal">
    <div class="modal-content max-w-sm">
        <div class="modal-header bg-red-600">
            <h3 class="text-lg font-bold">Konfirmasi Hapus</h3>
            <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        </div>
        <div class="modal-body text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="mb-2">Anda yakin ingin menghapus data:</p>
            <p class="text-lg font-bold text-red-600 mb-4" id="delete-nama"></p>
            <p class="text-sm text-gray-500 mb-4">NIK: <span id="delete-nik"></span></p>
            <p class="text-xs text-gray-400 mb-4">Aksi ini tidak dapat dibatalkan!</p>
            
            <div class="flex justify-center gap-2">
                <button onclick="closeModal('deleteModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                <a href="#" id="delete-confirm-link" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     JAVASCRIPT DEPENDENCIES & LOGIC
     --------------------------------------------------------------------------
     1. jQuery - Library JavaScript utama
     2. DataTables - Plugin jQuery untuk tabel interaktif
     3. Custom Functions - Modal handlers untuk CRUD
     ========================================================================== -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
// =============================================================================
// JAVASCRIPT: Inisialisasi DataTables
// Fungsi: Mengaktifkan fitur sorting, searching, pagination pada tabel
// =============================================================================
$(document).ready(function() {
    $('#tabelPeminjam').DataTable({
        "pageLength": 10,           // Tampilkan 10 data per halaman
        "order": [[5, "desc"]],    // Urutkan berdasarkan kolom ke-6 (Tanggal) descending
        "language": {
            "search": "Cari Cepat:",
            "lengthMenu": "_MENU_",
            "info": "Data _START_ - _END_ dari _TOTAL_",
            "paginate": {
                "next": "Next",
                "previous": "Prev"
            }
        }
    });
});

// =============================================================================
// FUNCTION: openDetailModal()
// Fungsi: Membuka modal detail dengan data peminjam
// Parameter: Data lengkap satu peminjam (dari PHP/Database)
// =============================================================================
function openDetailModal(nik, nama, alamat, kota, hp, pekerjaan, tanggal) {
    // Isi data ke elemen modal
    document.getElementById('detail-nama').textContent = nama;
    document.getElementById('detail-nik').textContent = nik;
    document.getElementById('detail-alamat').textContent = alamat;
    document.getElementById('detail-kota').textContent = kota;
    document.getElementById('detail-hp').textContent = hp;
    document.getElementById('detail-pekerjaan').textContent = pekerjaan;
    document.getElementById('detail-tanggal').textContent = tanggal;
    
    // Tampilkan modal
    document.getElementById('detailModal').style.display = 'block';
}

// =============================================================================
// FUNCTION: openEditModal()
// Fungsi: Membuka modal edit dan mengisi form dengan data yang ada
// Parameter: Data peminjam yang akan diedit
// =============================================================================
function openEditModal(nik, nama, alamat, kota, hp, pekerjaan) {
    // Isi form dengan data yang ada
    document.getElementById('edit-nik-lama').value = nik;    // Simpan NIK asli
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-nik').value = nik;
    document.getElementById('edit-alamat').value = alamat;
    document.getElementById('edit-kota').value = kota;
    document.getElementById('edit-hp').value = hp;
    document.getElementById('edit-pekerjaan').value = pekerjaan;
    
    // Tampilkan modal
    document.getElementById('editModal').style.display = 'block';
}

// =============================================================================
// FUNCTION: confirmDelete()
// Fungsi: Membuka modal konfirmasi delete sebelum menghapus data
// Parameter: nik (identifier), nama (untuk ditampilkan di modal)
// =============================================================================
function confirmDelete(nik, nama) {
    // Tampilkan data yang akan dihapus
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('delete-nik').textContent = nik;
    
    // Set link hapus dengan parameter NIK
    document.getElementById('delete-confirm-link').href = '?page=history&action=delete&nik=' + nik;
    
    // Tampilkan modal
    document.getElementById('deleteModal').style.display = 'block';
}

// =============================================================================
// FUNCTION: closeModal()
// Fungsi: Menutup modal berdasarkan ID
// Parameter: modalId - ID elemen modal yang akan ditutup
// =============================================================================
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// =============================================================================
// EVENT: Tutup modal jika user klik di luar modal content
// Fungsi: UX improvement - klik di luar modal = tutup modal
// =============================================================================
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<!-- ==========================================================================
     END OF FILE: history.php
     Lokasi: /opt/lampp/htdocs/RentCar/app/system/pages/history.php
     Fungsi: Halaman Riwayat dengan CRUD lengkap via Modal
     ========================================================================== -->
