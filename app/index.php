<?php
$page = $_GET['page'] ?? 'dashboard';

$allowed = ['dashboard', 'pinjaman', 'history'];

if (!in_array($page, $allowed)) {
    $page = 'dashboard';
}

include "system/layout/main.php";