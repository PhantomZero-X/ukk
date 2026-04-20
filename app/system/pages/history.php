<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$allData = ambilSemuaData();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Custom Styling agar DataTables cocok dengan Tailwind */
    .dataTables_wrapper .dataTables_length select {
        padding-right: 2rem;
        border-radius: 0.375rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.375rem;
        padding: 0.4rem;
        border: 1px solid #e2e8f0;
    }
    table.dataTable {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }
    table.dataTable thead th {
        border-bottom: 2px solid #e2e8f0 !important;
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold text-slate-800">Riwayat Peminjaman</h2>
    <div class="space-x-2">
        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
            CSV & JSON Active
        </span>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table id="tabelPeminjam" class="w-full text-left display nowrap">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">Nama Peminjam</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">NIK</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">Kota</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">No. HP</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">Pekerjaan</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold">Tgl Register</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($allData as $item): ?>
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($item['nama']) ?></td>
                    <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars($item['nik']) ?></td>
                    <td class="p-4 text-sm">
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs"><?= htmlspecialchars($item['kota']) ?></span>
                    </td>
                    <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars($item['hp']) ?></td>
                    <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars($item['pekerjaan']) ?></td>
                    <td class="p-4 text-xs text-gray-400 font-mono"><?= $item['tanggal'] ?></td>
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
            "order": [[5, "desc"]], // Urutkan berdasarkan tanggal register (kolom ke-6)
            "language": {
                "search": "Cari Data:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ peminjam",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Lanjut",
                    "previous": "Kembali"
                }
            }
        });
    });
</script>