<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3>Edit User</h3>

<form action="/users/update/<?= $user->id ?>" method="post">

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" value="<?= esc($user->username) ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?= esc($user->email) ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" <?= $user->role === 'owner' ? 'disabled' : '' ?>>
            <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="user" <?= $user->role == 'user' ? 'selected' : '' ?>>Kasir/User</option>
            <option value="owner" <?= $user->role == 'owner' ? 'selected' : '' ?>>(Owner)</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="/users" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
