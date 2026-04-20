<?php
// =============================================================================
// FILE: index.php (App Entry Point)
// LOKASI: /opt/lampp/htdocs/RentCar/app/index.php
// FUNGSI: Router halaman aplikasi dengan validasi security
//         - Membaca parameter 'page' dari URL
//         - Validasi halaman yang diizinkan (whitelist)
//         - Redirect ke layout utama dengan halaman yang valid
// SISTEM: Rental Mobil UKK WheelFlow
// SECURITY: Whitelist halaman yang diizinkan (prevent directory traversal)
// =============================================================================

// =============================================================================
// AMBIL PARAMETER PAGE dari URL menggunakan null coalescing operator (??)
// Syntax: $var = $value1 ?? $value2; 
//         // Jika $value1 ada, gunakan itu. Jika tidak, gunakan $value2.
// Contoh URL: /app/index.php?page=dashboard
// =============================================================================
$page = $_GET['page'] ?? 'dashboard';

// =============================================================================
// WHITELIST HALAMAN - Security measure untuk mencegah directory traversal
// Hanya halaman dalam array ini yang diizinkan di-load
// Directory traversal attack: hacker mencoba akses file sensitif seperti
// ../../../etc/passwd melalui parameter page
// =============================================================================
$allowed = ['dashboard', 'pinjaman', 'history'];

// =============================================================================
// VALIDASI PAGE - Cek apakah halaman yang diminta di whitelist
// in_array(): cek apakah value ada dalam array
// Jika tidak ada, redirect ke default (dashboard)
// =============================================================================
if (!in_array($page, $allowed)) {
    $page = 'dashboard';
}

// =============================================================================
// INCLUDE LAYOUT UTAMA - Load template master layout
// Layout akan include halaman yang sesuai dari parameter page
// File: /opt/lampp/htdocs/RentCar/app/system/layout/main.php
// =============================================================================
include "system/layout/main.php";

// =============================================================================
// END OF FILE: index.php (App)
// Lokasi: /opt/lampp/htdocs/RentCar/app/index.php
// Fungsi: Router dengan Whitelist Security
// ========================================================================== -->
