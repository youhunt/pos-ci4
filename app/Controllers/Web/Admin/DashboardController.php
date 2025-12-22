<?php

namespace App\Controllers\Web\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = user();

        return view('admin/dashboard', [
            'user' => $user
        ]);
    }
}
