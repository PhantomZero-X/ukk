<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$allData = ambilSemuaData();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    /* 1. Memberikan jarak antara search bar dengan tabel */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 20px; /* Jarak bawah search bar */
    }

    /* 2. Merapikan input search agar lebih kecil dan elegan */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db !important;
        border-radius: 0px !important; /* Tajam sesuai permintaan sebelumnya */
        padding: 4px 10px !important;
        margin-left: 10px !important;
        font-size: 0.875rem;
        outline: none;
    }

    /* 3. Memperkecil tombol navigasi (Next/Previous) */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 2px 10px !important;
        font-size: 0.75rem !important;
        border-radius: 0px !important;
    }

    /* 4. Memberikan jarak antara tabel dengan pagination di bawahnya */
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 20px !important;
        font-size: 0.875rem;
    }

    /* 5. Custom header tabel agar tetap tajam */
    table.dataTable thead th {
        border-bottom: 1px solid #1e293b !important;
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold text-slate-800">Riwayat Peminjaman</h2>
    <div class="text-xs text-gray-500 font-mono">Storage: CSV & JSON</div>
</div>

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
                    <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['nik']) ?></td>
                    <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['kota']) ?></td>
                    <td class="p-3 text-sm text-center text-gray-600"><?= htmlspecialchars($item['hp']) ?></td>
                    <td class="p-3 text-xs text-gray-400 font-mono"><?= $item['tanggal'] ?></td>
                    <td class="p-3">
                        <div class="flex justify-center gap-3">
                            <a href="#" class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="#" class="text-emerald-500 hover:text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <a href="#" class="text-red-500 hover:text-red-700">
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelPeminjam').DataTable({
            "pageLength": 10,
            "order": [[5, "desc"]],
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
</script>