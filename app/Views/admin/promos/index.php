<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h4>Daftar Promo</h4>

    <a href="<?= site_url('admin/promos/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Promo
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Promo</th>
                    <th>Tipe</th>
                    <th>Diskon</th>
                    <th>Berlaku</th>
                    <th>Hari</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if(empty($promos)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada promo
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $dayNames = [
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu'
                    ];
                    ?>

                    <?php foreach($promos as $p): ?>
                        <tr>
                            <td><?= esc($p['name']) ?></td>

                            <td>
                                <?= $p['type'] == 'product' ? 'Per Produk' : 'Per Kategori' ?>
                            </td>

                            <td>
                                <?php if($p['discount_type'] == 'percent'): ?>
                                    <?= $p['discount_value'] ?>%
                                <?php else: ?>
                                    Rp <?= number_format($p['discount_value'],0,',','.') ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= $p['start_date'] ?: '-' ?>  
                                s/d  
                                <?= $p['end_date'] ?: '-' ?><br>

                                <small class="text-muted">
                                    <?= $p['start_time'] ?: '00:00' ?> -
                                    <?= $p['end_time'] ?: '23:59' ?>
                                </small>
                            </td>

                            <td>
                                <?php if(!$p['weekdays']): ?>
                                    <span class="badge bg-success">Setiap Hari</span>
                                <?php else: ?>
                                    <?php 
                                        $d = explode(',', $p['weekdays']);
                                        $labels = array_map(fn($x)=>$dayNames[$x], $d);
                                        echo implode(', ', $labels);
                                    ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($p['active'] == 1): ?>
                                    <span class="badge bg-primary">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?= site_url('admin/promos/edit/'.$p['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="<?= site_url('admin/promos/delete/'.$p['id']) ?>"
                                   onclick="return confirm('Hapus promo ini?')"
                                   class="btn btn-sm btn-danger">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
