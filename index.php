<?php
// =============================================================================
// FILE: index.php (Root)
// LOKASI: /opt/lampp/htdocs/RentCar/index.php
// FUNGSI: Entry point pertama aplikasi saat diakses dari root folder
//         Redirect langsung ke layout utama sistem
// SISTEM: Rental Mobil UKK WheelFlow
// FLOW: Root index.php -> app/system/layout/main.php
// =============================================================================

// Redirect ke layout utama aplikasi
// header(): mengirim HTTP header ke browser
// Location: mengarahkan browser ke URL baru
header("Location: app/system/layout/main.php");

// exit: menghentikan eksekusi script setelah redirect
// mencegah kode di bawahnya dieksekusi
exit;

// =============================================================================
// END OF FILE: index.php (Root)
// Lokasi: /opt/lampp/htdocs/RentCar/index.php
// Fungsi: Redirect ke Main Layout
// ========================================================================== -->
