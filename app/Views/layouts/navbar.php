<?php $auth = service('authentication'); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="navbar-brand-box">
            <a href="<?= base_url('/');?>" class="logo logo-light">
                <span class="logo-lg">
                    <img src="<?= base_url('/assets/images/logo-light.png');?>" alt="" height="19">
                </span>
            </a>
        </div>
        <!-- <a class="navbar-brand" href="/dashboard">POS Toko</a> -->

        <div class="d-flex">
            <?php if ($auth->check()): ?>
                <a class="navbar-brand" href="<?= base_url('/dashboard');?>">Dashboard</a>
                <span class="navbar-text me-3">
                    <?= esc($auth->user()->username) ?>
                </span>

                <a href="<?= url_to('logout') ?>" class="btn btn-sm btn-outline-light">Logout</a>
            <?php endif ?>
        </div>
    </div>
</nav>
