<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WheelFlow - Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-900 text-white p-6 hidden md:block">
            <h1 class="text-2xl font-bold mb-10 text-blue-400">WheelFlow</h1>
            <nav class="space-y-4">
                <a href="?page=dashboard" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">Dashboard</a>
                <a href="?page=pinjaman" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">Tambah Pinjaman</a>
                <a href="?page=history" class="block py-2.5 px-4 rounded transition hover:bg-slate-800">Riwayat Data</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <?php 
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                include __DIR__ . "/../pages/$page.php"; 
            ?>
        </main>
    </div>
</body>
</html>