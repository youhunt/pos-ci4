<!DOCTYPE html>
<html>
<head>
    <title>Register Toko - POS Pena</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Daftar Toko Baru</h1>

    <?php if (session('errors')): ?>
        <div class="p-3 bg-red-100 text-red-700 rounded mb-4">
            <ul>
                <?php foreach(session('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= site_url('register') ?>" method="post">

        <h2 class="text-xl font-semibold mt-4">Data Akun</h2>

        <label>Nama Lengkap</label>
        <input class="w-full border p-2" name="fullname" value="<?= old('fullname') ?>">

        <label>Nama User Admin Toko</label>
        <input class="w-full border p-2" name="username" value="<?= old('username') ?>">

        <label class="mt-3 block">Email</label>
        <input class="w-full border p-2" name="email" type="email" value="<?= old('email') ?>">

        <label class="mt-3 block">Password</label>
        <input class="w-full border p-2" type="password" name="password">

        <label class="mt-3 block">Konfirmasi Password</label>
        <input class="w-full border p-2" type="password" name="pass_confirm">

        <h2 class="text-xl font-semibold mt-6">Data Toko</h2>

        <label>Nama Toko</label>
        <input class="w-full border p-2" name="shop_name" value="<?= old('shop_name') ?>">

        <label class="mt-3 block">Alamat (opsional)</label>
        <textarea class="w-full border p-2" name="shop_address"><?= old('shop_address') ?></textarea>

        <button class="w-full bg-indigo-600 text-white py-2 mt-5 rounded">
            Daftar & Buat Toko
        </button>

    </form>
</div>

</body>
</html>
