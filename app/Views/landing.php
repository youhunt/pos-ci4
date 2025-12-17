<?php $auth = service('authentication'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>POS Pena — Aplikasi Kasir Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="<?= base_url('/assets/images/logo.png'); ?>" class="h-10" alt="POS Pena">
                <span class="font-bold text-xl text-indigo-700">POS Pena</span>
            </div>

            <div class="space-x-6 hidden md:block">
                <a href="#features" class="hover:text-indigo-600">Fitur</a>
                <a href="#pricing" class="hover:text-indigo-600">Harga</a>
                <a href="#testimonials" class="hover:text-indigo-600">Testimoni</a>
                <?php if ($auth->check()): ?>
                    <a class="navbar-brand" href="<?= base_url('/dashboard');?>">Dashboard</a>
                    <span class="navbar-text me-3">
                        <?= esc($auth->user()->username) ?>
                    </span>

                    <a href="<?= url_to('logout') ?>" class="btn btn-sm btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('/login'); ?>" class="hover:text-indigo-600">Masuk</a>
                    <a href="<?= base_url('/register'); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Daftar
                    </a>
                <?php endif ?>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-indigo-600 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    POS Pena — Aplikasi Kasir Modern untuk Usaha Anda
                </h1>
                <p class="mt-4 text-lg opacity-90">
                    Kelola penjualan, stok, laporan, dan kasir Anda dengan mudah.  
                    Bisa offline, bisa PWA, auto-sync ketika online.
                </p>

                <div class="mt-8 flex space-x-4">
                    <a href="<?= base_url('/register'); ?>"
                       class="px-6 py-3 bg-white text-indigo-700 font-semibold rounded-lg shadow hover:bg-gray-100">
                        Daftar Toko Gratis
                    </a>
                    <a href="#features"
                       class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-indigo-700">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            <div class="flex justify-center">
                <img src="<?= base_url('/assets/images/mockup-pos.png'); ?>" alt="POS Mockup" class="rounded-xl shadow-xl w-full">
            </div>
        </div>
    </section>

    <!-- FITUR -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold text-gray-800">Fitur Utama</h2>
            <p class="mt-2 text-gray-600">Semua yang Anda butuhkan untuk menjalankan bisnis Anda</p>

            <div class="grid md:grid-cols-3 gap-10 mt-12">

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">Penjualan Cepat</h3>
                    <p class="text-gray-600">POS cepat, ringan, dan mudah digunakan bahkan saat offline.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">Manajemen Stok</h3>
                    <p class="text-gray-600">Pantau stok realtime, mutasi otomatis, dan aman.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">Varian & Harga</h3>
                    <p class="text-gray-600">Atur varian produk, ukuran, dan harga berbeda-beda.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">Multi User</h3>
                    <p class="text-gray-600">Akses kasir, admin, dan owner dengan role terpisah.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">Laporan Lengkap</h3>
                    <p class="text-gray-600">Laporan penjualan, stok, keuntungan, dan grafik.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-2">PWA Offline</h3>
                    <p class="text-gray-600">Install seperti aplikasi, bisa berjalan tanpa internet.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURE HIGHLIGHT -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h2 class="text-3xl font-bold">Bisa Dipakai Tanpa Internet</h2>
                <p class="mt-4 text-gray-600">
                    POS Pena menggunakan teknologi offline-first modern.  
                    Semua transaksi aman, dan otomatis sync ketika terhubung internet.
                </p>

                <ul class="mt-6 space-y-2 text-gray-700">
                    <li>✔ Tanpa koneksi tetap bisa menjual</li>
                    <li>✔ Auto sync saat internet kembali</li>
                    <li>✔ Data aman dengan sistem idempotent</li>
                </ul>
            </div>

            <div class="flex justify-center">
                <img src="<?= base_url('/assets/images/mockup-pos.png'); ?>" class="rounded-xl shadow-xl w-full">
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold">Paket Harga</h2>
            <p class="text-gray-600 mt-2">Pilih paket sesuai kebutuhan bisnis Anda</p>

            <div class="grid md:grid-cols-3 gap-10 mt-12">

                <!-- Starter -->
                <div class="bg-white p-8 rounded-xl shadow border">
                    <h3 class="text-xl font-bold">Starter</h3>
                    <p class="text-4xl mt-4 font-bold">Rp 0</p>
                    <p class="text-gray-500 text-sm">per bulan</p>

                    <ul class="mt-6 text-gray-700 space-y-2">
                        <li>✔ 1 Toko</li>
                        <li>✔ 2 User</li>
                        <li>✔ POS Offline</li>
                        <li>✔ Manajemen Produk</li>
                    </ul>

                    <a href="/register"
                       class="block mt-8 py-3 px-6 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                       Daftar Gratis
                    </a>
                </div>

                <!-- Pro -->
                <div class="bg-white p-8 rounded-xl shadow-xl border-2 border-indigo-600 scale-105">
                    <h3 class="text-xl font-bold text-indigo-700">Pro</h3>
                    <p class="text-4xl mt-4 font-bold">Rp 49.000</p>
                    <p class="text-gray-500 text-sm">per bulan</p>

                    <ul class="mt-6 text-gray-700 space-y-2">
                        <li>✔ Semua Starter</li>
                        <li>✔ Laporan Lengkap</li>
                        <li>✔ Support Printer Bluetooth</li>
                        <li>✔ Export Excel</li>
                        <li>✔ API Token</li>
                    </ul>

                    <a href="/register"
                       class="block mt-8 py-3 px-6 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                       Upgrade Pro
                    </a>
                </div>

                <!-- Bisnis -->
                <div class="bg-white p-8 rounded-xl shadow border">
                    <h3 class="text-xl font-bold">Bisnis</h3>
                    <p class="text-4xl mt-4 font-bold">Rp 99.000</p>
                    <p class="text-gray-500 text-sm">per bulan</p>

                    <ul class="mt-6 text-gray-700 space-y-2">
                        <li>✔ Semua Pro</li>
                        <li>✔ Multi Toko</li>
                        <li>✔ Multi Device</li>
                        <li>✔ Backup Harian</li>
                        <li>✔ Prioritas Support</li>
                    </ul>

                    <a href="/register"
                       class="block mt-8 py-3 px-6 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                       Hubungi Kami
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIAL -->
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold">Apa Kata Pengguna?</h2>
            <div class="grid md:grid-cols-2 gap-10 mt-12">

                <div class="bg-gray-50 p-8 rounded-xl shadow">
                    <p class="text-gray-700 italic">
                        “Bagus banget buat UMKM, gampang dipakai dan offline juga jalan!”
                    </p>
                    <p class="mt-4 font-bold text-indigo-700">— Budi, Kedai Kopi</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl shadow">
                    <p class="text-gray-700 italic">
                        “Sinkronisasinya cepat! Kasir aman meski listrik mati.”
                    </p>
                    <p class="mt-4 font-bold text-indigo-700">— Ana, Toko Sembako</p>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-10 bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-lg font-bold text-white">POS Pena</p>
            <p class="mt-2">Aplikasi kasir modern untuk UMKM Indonesia</p>

            <div class="mt-6 space-x-6">
                <a href="#" class="hover:text-white">Tentang</a>
                <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white">Kontak</a>
            </div>

            <p class="mt-6 text-sm text-gray-500">
                © 2025 POS Pena — All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
