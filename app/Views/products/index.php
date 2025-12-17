<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h4>Daftar Produk</h4>

    <div>
        <a href="/products/create" class="btn btn-primary">+ Produk Baru</a>
        <a href="/products/import-template" class="btn btn-success">Download Template</a>

        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
            Import CSV
        </button>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Nama</th>
            <th>Barcode</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= esc($p['sku']) ?></td>
            <td><?= esc($p['name']) ?></td>
            <td><?= esc($p['barcode']) ?></td>
            <td><?= number_format($p['price'],0,',','.') ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
                <?php if ($p['image']): ?>
                    <img src="/uploads/products/<?= $p['image'] ?>" width="50">
                <?php endif ?>
            </td>
            <td>
                <a href="/products/edit/<?= $p['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                <a href="/products/delete/<?= $p['id'] ?>" 
                   onclick="return confirm('Hapus produk ini?')"
                   class="btn btn-sm btn-danger">
                    Hapus
                </a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/products/import" method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Produk CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Upload file CSV sesuai template.</p>
                <input type="file" name="file_csv" accept=".csv" required class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
