<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-4">
    Selamat datang, <?= esc($user->username) ?>
</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded shadow">Produk</div>
    <div class="bg-white p-4 rounded shadow">Promo</div>
    <div class="bg-white p-4 rounded shadow">User</div>
    <div class="bg-white p-4 rounded shadow">Laporan</div>
</div>

<?= $this->endSection() ?>
