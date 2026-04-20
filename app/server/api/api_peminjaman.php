<?php
// ============================================================================
// FILE: api_peminjaman.php
// LOKASI: /opt/lampp/htdocs/RentCar/app/server/api/api_peminjaman.php
// FUNGSI: API untuk operasi CRUD (Create, Read, Update, Delete) data peminjam
// SISTEM: Rental Mobil UKK WheelFlow
// STORAGE: CSV & JSON (dual storage untuk backup)
// ============================================================================

/**
 * =========================================================================
 * FUNGSI CREATE: simpanDataPeminjam()
 * -------------------------------------------------------------------------
 * Fungsi untuk menyimpan data peminjam baru ke file CSV dan JSON secara 
 * bersamaan sebagai backup ganda (dual storage system).
 * 
 * Sumber parameter: Form input dari pinjaman.php
 * Struktur data: [nama, nik, alamat, kota, hp, pekerjaan]
 * 
 * Return:
 *   - bool true        : Jika penyimpanan berhasil
 *   - string error_msg : Jika terjadi error (permission, dll)
 * =========================================================================
 */
function simpanDataPeminjam($data_array) {
    // Lokasi folder data dan file
    $data_dir = __DIR__ . '/../data';  // Direktori data ada di /app/server/data/
    $path_csv = $data_dir . '/peminjam.csv';   // File CSV storage
    $path_json = $data_dir . '/peminjam.json'; // File JSON storage

    // [STEP 1] Cek dan buat folder data jika belum ada
    // Permission 0755: owner bisa read/write/execute, group & others read/execute
    if (!is_dir($data_dir)) {
        if (!mkdir($data_dir, 0755, true)) {
            return "Gagal membuat folder data. Cek permission.";
        }
    }

    // [STEP 2] Cek apakah folder writable (bisa ditulis)
    if (!is_writable($data_dir)) {
        return "Folder data tidak writable. Cek permission folder.";
    }

    // [STEP 3] Tambahkan timestamp registrasi otomatis
    $data_array['tanggal_register'] = date('Y-m-d H:i:s');
    // array_values() mengembalikan array dengan index numerik 0,1,2,...
    $formatted_data = array_values($data_array);

    // [STEP 4] Simpan ke CSV (Comma Separated Values)
    // Mode 'a' = append (menambah data di akhir file, tidak overwrite)
    $file_csv = fopen($path_csv, 'a');
    if ($file_csv === false) {
        return "Gagal membuka file CSV untuk ditulis.";
    }

    // fputcsv: menulis array ke file CSV dengan format otomatis (escape comma, dll)
    $write_result = fputcsv($file_csv, $formatted_data);
    fclose($file_csv); // Tutup file setelah selesai

    if ($write_result === false) {
        return "Gagal menulis data ke file CSV.";
    }

    // [STEP 5] Simpan ke JSON (JavaScript Object Notation)
    // JSON lebih mudah dibaca/diproses oleh JavaScript dan API
    $current_json_data = [];
    if (file_exists($path_json)) {
        $json_content = file_get_contents($path_json);
        $current_json_data = json_decode($json_content, true) ?? [];
    }

    // Map data array ke associative array dengan key yang jelas
    $new_entry = [
        'nama' => $data_array[0],
        'nik' => $data_array[1],
        'alamat' => $data_array[2],
        'kota' => $data_array[3],
        'hp' => $data_array[4],
        'pekerjaan' => $data_array[5],
        'tanggal_register' => $data_array['tanggal_register']
    ];

    $current_json_data[] = $new_entry;
    // JSON_PRETTY_PRINT: format JSON dengan indentation agar mudah dibaca
    $json_result = file_put_contents($path_json, json_encode($current_json_data, JSON_PRETTY_PRINT));

    if ($json_result === false) {
        return "Gagal menulis data ke file JSON.";
    }

    return true;
}

/**
 * =========================================================================
 * FUNGSI READ: ambilSemuaData()
 * -------------------------------------------------------------------------
 * Fungsi untuk mengambil semua data peminjam dari file CSV.
 * Data diurutkan dari yang terbaru (reverse order) berdasarkan urutan
 * input di file.
 * 
 * Return:
 *   - array : Array of associative array berisi data peminjam
 *   - empty array : Jika file tidak ada atau kosong
 * =========================================================================
 */
function ambilSemuaData() {
    $filePath = __DIR__ . '/../data/peminjam.csv';
    $results = [];
    
    // Jika file belum ada, return array kosong (belum ada data)
    if (!file_exists($filePath)) return [];

    // Buka file CSV dengan mode read ('r')
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        // Loop membaca setiap baris CSV
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Setiap baris CSV diparse menjadi array dan dimapping ke key
            $results[] = [
                'nama' => $data[0],
                'nik' => $data[1],
                'alamat' => $data[2],
                'kota' => $data[3],
                'hp' => $data[4],
                'pekerjaan' => $data[5],
                'tanggal' => $data[6]
            ];
        }
        fclose($handle);
    }
    // array_reverse: mengurutkan data terbaru di atas (LIFO - Last In First Out)
    return array_reverse($results);
}

/**
 * =========================================================================
 * FUNGSI READ: ambilDataByNIK()
 * -------------------------------------------------------------------------
 * Fungsi untuk mengambil detail data satu peminjam berdasarkan NIK.
 * Digunakan untuk modal detail (icon Read/View).
 * 
 * Parameter:
 *   - $nik : Nomor Induk Kependudukan peminjam
 * 
 * Return:
 *   - array : Data peminjam yang dicari
 *   - null  : Jika tidak ditemukan
 * =========================================================================
 */
function ambilDataByNIK($nik) {
    $filePath = __DIR__ . '/../data/peminjam.csv';
    if (!file_exists($filePath)) return null;

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Bandingkan NIK dengan data di CSV (case-insensitive)
            if (strcasecmp($data[1], $nik) === 0) {
                fclose($handle);
                return [
                    'nama' => $data[0],
                    'nik' => $data[1],
                    'alamat' => $data[2],
                    'kota' => $data[3],
                    'hp' => $data[4],
                    'pekerjaan' => $data[5],
                    'tanggal' => $data[6]
                ];
            }
        }
        fclose($handle);
    }
    return null;
}

/**
 * =========================================================================
 * FUNGSI UPDATE: updateDataPeminjam()
 * -------------------------------------------------------------------------
 * Fungsi untuk mengupdate data peminjam yang sudah ada berdasarkan NIK.
 * Menggunakan NIK sebagai unique identifier karena setiap orang punya
 * NIK yang berbeda.
 * 
 * Parameter:
 *   - $nik          : NIK peminjam yang akan diupdate (identifier)
 *   - $data_baru    : Array data baru [nama, nik, alamat, kota, hp, pekerjaan]
 * 
 * Return:
 *   - bool true     : Jika update berhasil
 *   - bool false    : Jika data tidak ditemukan atau gagal update
 * =========================================================================
 */
function updateDataPeminjam($nik, $data_baru) {
    $data_dir = __DIR__ . '/../data';
    $path_csv = $data_dir . '/peminjam.csv';
    $path_json = $data_dir . '/peminjam.json';
    
    if (!file_exists($path_csv)) return false;

    // [STEP 1] Baca semua data CSV
    $allData = [];
    $found = false;
    if (($handle = fopen($path_csv, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Jika NIK cocok, ganti dengan data baru (kecuali tanggal asli)
            if (strcasecmp($data[1], $nik) === 0) {
                $allData[] = array_values($data_baru);
                $found = true;
            } else {
                $allData[] = $data;
            }
        }
        fclose($handle);
    }

    if (!$found) return false;

    // [STEP 2] Tulis ulang file CSV dengan data yang sudah diupdate
    $file_csv = fopen($path_csv, 'w'); // Mode 'w' = write (overwrite)
    if ($file_csv === false) return false;
    
    foreach ($allData as $row) {
        fputcsv($file_csv, $row);
    }
    fclose($file_csv);

    // [STEP 3] Update juga file JSON
    $json_data = [];
    foreach ($allData as $row) {
        $json_data[] = [
            'nama' => $row[0],
            'nik' => $row[1],
            'alamat' => $row[2],
            'kota' => $row[3],
            'hp' => $row[4],
            'pekerjaan' => $row[5],
            'tanggal_register' => $row[6]
        ];
    }
    file_put_contents($path_json, json_encode($json_data, JSON_PRETTY_PRINT));

    return true;
}

/**
 * =========================================================================
 * FUNGSI DELETE: hapusDataPeminjam()
 * -------------------------------------------------------------------------
 * Fungsi untuk menghapus data peminjam berdasarkan NIK.
 * Menghapus dari kedua file (CSV dan JSON) untuk konsistensi data.
 * 
 * Parameter:
 *   - $nik : NIK peminjam yang akan dihapus
 * 
 * Return:
 *   - bool true  : Jika penghapusan berhasil
 *   - bool false : Jika data tidak ditemukan atau gagal hapus
 * =========================================================================
 */
function hapusDataPeminjam($nik) {
    $data_dir = __DIR__ . '/../data';
    $path_csv = $data_dir . '/peminjam.csv';
    $path_json = $data_dir . '/peminjam.json';
    
    if (!file_exists($path_csv)) return false;

    // [STEP 1] Baca semua data kecuali yang akan dihapus
    $allData = [];
    $found = false;
    if (($handle = fopen($path_csv, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Skip data yang NIK-nya cocok (tidak dimasukkan ke array baru)
            if (strcasecmp($data[1], $nik) !== 0) {
                $allData[] = $data;
            } else {
                $found = true;
            }
        }
        fclose($handle);
    }

    if (!$found) return false;

    // [STEP 2] Tulis ulang CSV tanpa data yang dihapus
    $file_csv = fopen($path_csv, 'w');
    if ($file_csv === false) return false;
    
    foreach ($allData as $row) {
        fputcsv($file_csv, $row);
    }
    fclose($file_csv);

    // [STEP 3] Update JSON juga
    $json_data = [];
    foreach ($allData as $row) {
        $json_data[] = [
            'nama' => $row[0],
            'nik' => $row[1],
            'alamat' => $row[2],
            'kota' => $row[3],
            'hp' => $row[4],
            'pekerjaan' => $row[5],
            'tanggal_register' => $row[6]
        ];
    }
    file_put_contents($path_json, json_encode($json_data, JSON_PRETTY_PRINT));

    return true;
}

/**
 * =========================================================================
 * END OF FILE: api_peminjaman.php
 * Total Fungsi: 5 (Create, Read All, Read One, Update, Delete)
 * Lokasi: /opt/lampp/htdocs/RentCar/app/server/api/api_peminjaman.php
 * =========================================================================
 */
?>

?>