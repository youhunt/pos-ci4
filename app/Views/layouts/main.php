<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Aplikasi') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

    <?= $this->include('layouts/navbar') ?>

    <main class="py-4">
        <div class="container-fluid">
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container">
            <span class="text-muted">&copy; <?= date('Y') ?> Pena Inovasi Sistem</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts'); ?>

</body>
</html>
