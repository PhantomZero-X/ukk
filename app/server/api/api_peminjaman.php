<?php
/**
 * Fungsi untuk menyimpan data ke file CSV dan JSON
 * @param array $data Data dari form post
 * @return bool|string True jika sukses, string error message jika gagal
 */
function simpanDataPeminjam($data_array) {
    $data_dir = __DIR__ . '/../data';
    $path_csv = $data_dir . '/peminjam.csv';
    $path_json = $data_dir . '/peminjam.json';

    // 1. Cek dan buat folder data jika belum ada
    if (!is_dir($data_dir)) {
        if (!mkdir($data_dir, 0755, true)) {
            return "Gagal membuat folder data. Cek permission.";
        }
    }

    // 2. Cek apakah folder writable
    if (!is_writable($data_dir)) {
        return "Folder data tidak writable. Cek permission folder.";
    }

    // Tambahkan tanggal register
    $data_array['tanggal_register'] = date('Y-m-d H:i:s');
    $formatted_data = array_values($data_array);

    // 3. Simpan ke CSV dengan error handling
    $file_csv = fopen($path_csv, 'a');
    if ($file_csv === false) {
        return "Gagal membuka file CSV untuk ditulis.";
    }

    $write_result = fputcsv($file_csv, $formatted_data);
    fclose($file_csv);

    if ($write_result === false) {
        return "Gagal menulis data ke file CSV.";
    }

    // 4. Simpan ke JSON dengan error handling
    $current_json_data = [];
    if (file_exists($path_json)) {
        $json_content = file_get_contents($path_json);
        $current_json_data = json_decode($json_content, true) ?? [];
    }

    // Map data agar JSON memiliki key yang jelas
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
    $json_result = file_put_contents($path_json, json_encode($current_json_data, JSON_PRETTY_PRINT));

    if ($json_result === false) {
        return "Gagal menulis data ke file JSON.";
    }

    return true;
}

/**
 * Fungsi untuk mengambil semua data dari CSV
 * @return array
 */
function ambilSemuaData() {
    $filePath = __DIR__ . '/../data/peminjam.csv';
    $results = [];
    if (!file_exists($filePath)) return [];

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
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
    return array_reverse($results);
}
?>