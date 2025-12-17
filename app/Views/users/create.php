<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3>Tambah User Baru</h3>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/users/save" method="post">
    <div class="mb-3">
        <label>Username</label>
        <input name="username" class="form-control" value="<?= old('username') ?>">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" value="<?= old('email') ?>">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control">
    </div>

    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input name="pass_confirm" type="password" class="form-control">
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="admin">Admin</option>
            <option value="user">Kasir</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

<?= $this->endSection() ?>
