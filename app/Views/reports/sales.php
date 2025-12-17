<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4>Laporan Penjualan</h4>

<form method="get" class="row mb-4">
    <div class="col-md-3">
        <label>Dari tanggal</label>
        <input type="date" name="start_date" value="<?= $start ?>" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Sampai tanggal</label>
        <input type="date" name="end_date" value="<?= $end ?>" class="form-control">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <a href="/reports/sales/export/excel?start_date=<?= $start ?>&end_date=<?= $end ?>" class="btn btn-success w-100 me-2">Excel</a>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Total Omzet</h6>
            <h3>Rp <?= number_format($total_sales) ?></h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h6>Total Item Terjual</h6>
            <h3><?= number_format($total_items) ?></h3>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Invoice</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
            <td><?= $t['invoice'] ?></td>
            <td>Rp <?= number_format($t['total_amount']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
