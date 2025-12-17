<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4><?= isset($promo) ? 'Edit Promo' : 'Tambah Promo' ?></h4>

<form action="<?= isset($promo) ? site_url('promo/update/'.$promo['id']) : site_url('promo/store') ?>" method="post">

    <div class="mb-3">
        <label>Nama Promo</label>
        <input type="text" name="name" class="form-control"
            value="<?= isset($promo) ? esc($promo['name']) : '' ?>" required>
    </div>

    <!-- TIPE PROMO -->
    <div class="mb-3">
        <label>Tipe Promo</label>
        <select name="type" id="promoType" class="form-control" required>
            <option value="product" <?= isset($promo) && $promo['type']=='product'?'selected':'' ?>>Per Produk</option>
            <option value="category" <?= isset($promo) && $promo['type']=='category'?'selected':'' ?>>Per Kategori</option>
            <option value="global" <?= isset($promo) && $promo['type']=='global'?'selected':'' ?>>Semua Produk</option>
        </select>
    </div>

    <!-- PRODUK -->
    <div class="mb-3" id="productSelect" style="display:none;">
        <label>Pilih Produk (multi pilih)</label>
        <select name="products[]" class="form-control" multiple size="6">
            <?php foreach($products as $p): 
                $selected = '';
                if(isset($promoProducts)) {
                    foreach($promoProducts as $pp) {
                        if($pp['product_id']==$p['id']) $selected = 'selected';
                    }
                }
            ?>
                <option value="<?= $p['id'] ?>" <?= $selected ?>><?= esc($p['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <small class="text-muted">Gunakan Ctrl/Cmd untuk pilih lebih dari satu</small>
    </div>

    <!-- KATEGORI -->
    <div class="mb-3" id="categorySelect" style="display:none;">
        <label>Pilih Kategori</label>
        <select name="category_id" class="form-control">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>" 
                    <?= isset($promo) && $promo['category_id']==$c['id'] ? 'selected' : '' ?>>
                    <?= esc($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- JENIS DISKON -->
    <div class="mb-3">
        <label>Jenis Diskon</label>
        <select name="discount_type" class="form-control" required>
            <option value="percent" <?= isset($promo) && $promo['discount_type']=='percent'?'selected':'' ?>>Persen (%)</option>
            <option value="nominal" <?= isset($promo) && $promo['discount_type']=='nominal'?'selected':'' ?>>Potongan Nominal (Rp)</option>
            <option value="fixed_price" <?= isset($promo) && $promo['discount_type']=='fixed_price'?'selected':'' ?>>Harga Tetap</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Nilai Diskon</label>
        <input type="number" step="0.01" name="discount_value" class="form-control"
            value="<?= isset($promo) ? esc($promo['discount_value']) : 0 ?>" required>
    </div>

    <!-- DATE RANGE -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control"
                value="<?= isset($promo) ? esc($promo['start_date']) : '' ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label>Tanggal Berakhir</label>
            <input type="date" name="end_date" class="form-control"
                value="<?= isset($promo) ? esc($promo['end_date']) : '' ?>">
        </div>
    </div>

    <!-- TIME RANGE -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Jam Mulai</label>
            <input type="time" name="start_time" class="form-control"
                value="<?= isset($promo) ? esc($promo['start_time']) : '' ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label>Jam Berakhir</label>
            <input type="time" name="end_time" class="form-control"
                value="<?= isset($promo) ? esc($promo['end_time']) : '' ?>">
        </div>
    </div>

    <!-- WEEKDAYS -->
    <?php 
    $days = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu'
    ];

    $selectedDays = isset($promo['weekdays']) && $promo['weekdays']
        ? explode(',', $promo['weekdays'])
        : [];
    ?>

    <div class="mb-3">
        <label><strong>Hari Aktif</strong></label>

        <div class="row">
            <?php foreach($days as $num => $label): ?>
                <div class="col-6 col-md-4">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="day-<?= $num ?>" 
                            name="weekdays[]" 
                            value="<?= $num ?>" 
                            <?= in_array($num, $selectedDays) ? 'checked' : '' ?>
                        >
                        <label class="form-check-label" for="day-<?= $num ?>">
                            <?= $label ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <small class="text-muted">Kosongkan jika promo berlaku setiap hari.</small>
    </div>

    <!-- STATUS -->
    <div class="mb-3 form-check">
        <input type="checkbox" name="active" class="form-check-input" id="active"
            <?= isset($promo) && $promo['active'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="active">Aktif</label>
    </div>

    <button type="submit" class="btn btn-primary"><?= isset($promo) ? 'Update' : 'Simpan' ?></button>
</form>

<script>
// Show/Hide product/category select
function togglePromoType() {
    const t = document.getElementById('promoType').value;
    document.getElementById('productSelect').style.display = (t === 'product') ? '' : 'none';
    document.getElementById('categorySelect').style.display = (t === 'category') ? '' : 'none';
}
togglePromoType();

document.getElementById('promoType').addEventListener('change', togglePromoType);
</script>

<?= $this->endSection() ?>
