<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4>Edit Produk</h4>

<form action="/products/update/<?= $product['id'] ?>" method="post" enctype="multipart/form-data" class="mt-3">

    <div class="mb-3">
        <label>SKU</label>
        <input type="text" name="sku" class="form-control" value="<?= esc($product['sku']) ?>">
    </div>

    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="<?= esc($product['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Barcode</label>
        <input type="text" name="barcode" class="form-control" value="<?= esc($product['barcode']) ?>">
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Harga Grosir</label>
        <input type="number" name="wholesale_price" class="form-control" value="<?= $product['wholesale_price'] ?>">
    </div>

    <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>">
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"><?= esc($product['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Gambar</label><br>
        <?php if ($product['image']): ?>
            <img src="/uploads/products/<?= $product['image'] ?>" width="70" class="mb-2">
        <?php endif ?>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="/products" class="btn btn-secondary">Kembali</a>

</form>

<?= $this->endSection() ?>
