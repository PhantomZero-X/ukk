<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$allData = ambilSemuaData();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    /* Styling Custom untuk merapikan DataTables */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0f172a !important;
        color: white !important;
        border-radius: 0.375rem;
        border: none;
    }
    table.dataTable thead th {
        background-color: #f8fafc;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    /* Memastikan tabel tidak melengkung di bagian container datanya */
    .data-table-container {
        border-radius: 0px !important; 
    }
</style>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-3xl font-bold text-slate-800">Riwayat Peminjaman</h2>
        <p class="text-slate-500 text-sm">Manajemen data transaksi rental mobil</p>
    </div>
    <div class="flex gap-2">
        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-md flex items-center">
            <i class="fas fa-file-code mr-1"></i> JSON & CSV
        </span>
    </div>
</div>

<div class="bg-white p-6 shadow-sm border border-gray-100 data-table-container">
    <div class="overflow-x-auto">
        <table id="tabelPeminjam" class="w-full text-left display nowrap">
            <thead>
                <tr>
                    <th class="p-4">Nama Peminjam</th>
                    <th class="p-4">NIK</th>
                    <th class="p-4">Kota</th>
                    <th class="p-4">No. HP</th>
                    <th class="p-4">Register</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($allData as $index => $item): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-semibold text-slate-700"><?= htmlspecialchars($item['nama']) ?></td>
                    <td class="p-4 text-sm text-slate-600"><?= htmlspecialchars($item['nik']) ?></td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-xs font-medium">
                            <?= htmlspecialchars($item['kota']) ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-600"><?= htmlspecialchars($item['hp']) ?></td>
                    <td class="p-4 text-xs text-slate-400 font-mono"><?= date('d/m/Y H:i', strtotime($item['tanggal'])) ?></td>
                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            <button onclick="alert('Detail: <?= $item['nama'] ?>')" class="w-8 h-8 flex items-center justify-center rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm" title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition shadow-sm" title="Edit Data">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus Data">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
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
            "order": [[4, "desc"]], // Urutkan berdasarkan tanggal register
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Matikan sorting untuk kolom Aksi
            ],
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data peminjam...",
                "lengthMenu": "_MENU_",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "<i class='fas fa-chevron-right'></i>",
                    "previous": "<i class='fas fa-chevron-left'></i>"
                }
            }
        });

        // Merapikan filter pencarian agar terlihat lebih Tailwind-ish
        $('.dataTables_filter').addClass('flex justify-end');
    });
</script>