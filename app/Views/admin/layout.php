<!doctype html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <aside class="w-64 bg-white border-r p-4">
        <h2 class="font-bold text-xl mb-6">POS Admin</h2>

        <nav class="space-y-2">
            <a href="/admin" class="block p-2 hover:bg-gray-100 rounded">Dashboard</a>
            <a href="/admin/products" class="block p-2 hover:bg-gray-100 rounded">Produk</a>
            <a href="/admin/promos" class="block p-2 hover:bg-gray-100 rounded">Promo</a>
            <a href="/admin/users" class="block p-2 hover:bg-gray-100 rounded">User</a>
            <a href="/logout" class="block p-2 text-red-600">Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-6">
        <?= $this->renderSection('content') ?>
    </main>

</div>

</body>
</html>
