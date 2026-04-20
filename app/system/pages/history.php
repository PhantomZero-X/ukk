<?php
include_once __DIR__ . '/../../server/api/api_peminjaman.php';
$allData = ambilSemuaData();
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    /* Styling agar tabel tetap tajam (tidak melengkung) */
    #tabelPeminjam { border-radius: 0 !important; }
    #tabelPeminjam th, #tabelPeminjam td { border-radius: 0 !important; }
    
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0;
        border: 1px solid #cbd5e1;
        padding: 0.2rem;
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold text-slate-800">Riwayat Peminjaman</h2>
</div>

<div class="bg-white p-6 shadow-sm border border-gray-200">
    <table id="tabelPeminjam" class="w-full text-left">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="p-3 text-xs uppercase font-bold text-center">No</th>
                <th class="p-3 text-xs uppercase font-bold">Nama</th>
                <th class="p-3 text-xs uppercase font-bold">NIK</th>
                <th class="p-3 text-xs uppercase font-bold">Kota</th>
                <th class="p-3 text-xs uppercase font-bold">HP</th>
                <th class="p-3 text-xs uppercase font-bold">Register</th>
                <th class="p-3 text-xs uppercase font-bold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $no = 1; foreach($allData as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="p-3 text-center text-sm font-bold"><?= $no++ ?></td>
                <td class="p-3 text-sm font-medium text-gray-800"><?= htmlspecialchars($item['nama']) ?></td>
                <td class="p-3 text-sm text-gray-600"><?= htmlspecialchars($item['nik']) ?></td>
                <td class="p-3 text-sm text-gray-600"><?= htmlspecialchars($item['kota']) ?></td>
                <td class="p-3 text-sm text-gray-600"><?= htmlspecialchars($item['hp']) ?></td>
                <td class="p-3 text-xs text-gray-400 font-mono"><?= $item['tanggal'] ?></td>
                <td class="p-3 flex justify-center gap-2">
                    <button class="text-blue-600 hover:text-blue-800" title="Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button class="text-emerald-600 hover:text-emerald-800" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button class="text-red-600 hover:text-red-800" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelPeminjam').DataTable({
            "order": [[5, "desc"]],
            "language": { "search": "Cari:" }
        });
    });
</script>