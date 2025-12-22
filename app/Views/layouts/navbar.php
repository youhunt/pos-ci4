<?php $auth = service('authentication'); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <!-- BRAND -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="<?= base_url('/assets/images/logo-light.png'); ?>" height="20" class="me-2">
        </a>

        <!-- TOGGLER (MOBILE) -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="adminNavbar">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == 'admin' ? 'active' : '' ?>" href="/admin">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(), 'admin/products') ? 'active' : '' ?>" href="/admin/products">
                        Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(), 'admin/promos') ? 'active' : '' ?>" href="/admin/promos">
                        Promo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(), 'admin/users') ? 'active' : '' ?>" href="/admin/users">
                        User
                    </a>
                </li>
            </ul>

            <!-- RIGHT MENU -->
            <?php if ($auth->check()): ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown">
                            <?= esc($auth->user()->username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/admin">
                                    Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= url_to('logout') ?>">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</nav>
