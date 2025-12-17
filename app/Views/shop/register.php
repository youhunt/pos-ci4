<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Daftarkan Toko Anda</h2>

<form action="/shop/register" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div>
        <label>Nama Toko</label>
        <input type="text" name="name" value="<?= old('name') ?>" required>
    </div>

    <div>
        <label>Nama User Admin</label>
        <input type="text" name="username" value="<?= old('username') ?>" required>
    </div>

    <div>
        <label>Alamat</label>
        <textarea name="address"><?= old('address') ?></textarea>
    </div>

    <div>
        <label>Logo (opsional)</label>
        <input type="file" name="logo">
    </div>

    <button type="submit">Simpan</button>
</form>

<?= $this->endSection() ?>
