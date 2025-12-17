<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3>Daftar User Toko</h3>

<a href="/users/create" class="btn btn-primary mb-3">+ Tambah User</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= esc($u->username) ?></td>
                <td><?= esc($u->email) ?></td>
                <td>
                    <?= user_group($u->id) ?>
                </td>
                <td>
                    <a href="/users/edit/<?= $u->id ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/users/delete/<?= $u->id ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Hapus user ini?')">Delete</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $this->endSection() ?>
