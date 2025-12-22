<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4>Tambah Produk</h4>

<form action="/products/store" method="post" enctype="multipart/form-data" class="mt-3">

    <div class="mb-3">
        <label>SKU</label>
        <input type="text" name="sku" class="form-control">
    </div>

    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Barcode</label>
        <input type="text" name="barcode" class="form-control">
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga Grosir</label>
        <input type="number" name="wholesale_price" class="form-control">
    </div>

    <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="0">
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="/products" class="btn btn-secondary">Kembali</a>

</form>

<?= $this->endSection() ?>
