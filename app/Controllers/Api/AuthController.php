<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        $auth = service('authentication');

        $data = $this->request->getJSON(true);

        $login    = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$login || !$password) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Username & password wajib diisi'
            ]);
        }

        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!$auth->attempt([$type => $login, 'password' => $password])) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => $auth->error() ?? 'Login gagal'
            ]);
        }

        $user = $auth->user();

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'id'       => $user->id,
                'username' => $user->username,
                'shop_id'  => $user->shop_id,
                'role' => 'admin' // sementara hardcode, nanti bisa diambil dari database
            ]
        ]);
    }

    public function logout()
    {
        $auth = service('authentication');
        $auth->logout();

        return $this->response->setJSON([
            'status' => 'ok'
        ]);
    }
}