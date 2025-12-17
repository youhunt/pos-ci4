<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">

    <h3 class="mb-4 fw-bold">Dashboard</h3>

    <div class="row g-4">

        <!-- Produk -->
        <div class="col-md-3 col-6">
            <a href="/products" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-box-seam display-5 text-primary"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Produk</h5>
                        <p class="text-muted small mb-0">Kelola barang & stok</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Penjualan -->
        <div class="col-md-3 col-6">
            <a href="/reports/sales" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-receipt-cutoff display-5 text-success"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Penjualan</h5>
                        <p class="text-muted small mb-0">Transaksi & laporan</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Penjualan / POS -->
        <div class="col-md-3 col-6">
            <a href="/pos" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-receipt-cutoff display-5 text-success"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Transaksi POS</h5>
                        <p class="text-muted small mb-0">Input transaksi & scan barcode</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Promo / Diskon -->
        <div class="col-md-3 col-6">
            <a href="/promo" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-tags-fill display-5 text-warning"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Promo / Diskon</h5>
                        <p class="text-muted small mb-0">Atur diskon produk & kategori</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- User -->
        <div class="col-md-3 col-6">
            <a href="/users" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-people-fill display-5 text-warning"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">User</h5>
                        <p class="text-muted small mb-0">Kelola kasir & karyawan</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Settings -->
        <div class="col-md-3 col-6">
            <a href="/settings" class="text-decoration-none">
                <div class="card shadow-sm border-0 menu-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-gear-fill display-5 text-danger"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Pengaturan</h5>
                        <p class="text-muted small mb-0">Profil toko & preferensi</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<style>
    .menu-card {
        transition: 0.2s;
        border-radius: 12px;
    }
    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
</style>

<?= $this->endSection() ?>
