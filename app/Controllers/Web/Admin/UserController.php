<?php

namespace App\Controllers\Web\Admin;

use App\Controllers\BaseController;
use \Myth\Auth\Models\UserModel;
use \Myth\Auth\Password;
use \Myth\Auth\Entities\User;
use \Myth\Auth\Authorization\GroupModel;
use \Myth\Auth\Config\Auth as AuthConfig;

class UserController extends BaseController
{
    public function index()
    {
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }
        $userModel = new UserModel();
        $users = $userModel->where('shop_id', $currentUser->shop_id)->findAll();

        return view('admin/users/index', [
            'users' => $users,
            'shopId' => $currentUser->shop_id
        ]);
    }

    public function create()
    {
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }

        return view('admin/users/create');
    }

    public function save()
    {
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }

        $users = model(UserModel::class);

        $rules = [
            'username'     => 'required|min_length[3]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
            'role'         => 'required|in_list[admin,kasir]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- create user ---
        $user = new User($this->request->getPost());
        $user->shop_id = $currentUser->shop_id; // wajib

        $user->activate();

        $users = $users->withGroup($this->request->getPost('role'));

        if (! $users->save($user)) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        return redirect()->to('/users')->with('message', 'User berhasil dibuat!');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }

        // pastikan user yg diedit milik toko yg sama
        $user = $userModel->where('shop_id', $currentUser->shop_id)->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        return view('admin/users/edit', [
            'user' => $user
        ]);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }

        $user = $userModel->where('shop_id', $currentUser->shop_id)->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        // validasi simple
        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'     => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // owner tidak bisa diubah
        if ($user->role === 'owner' && $this->request->getPost('role') !== 'owner') {
            return redirect()->back()->with('error', 'Role owner tidak bisa diubah.');
        }

        $userModel->update($id, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to('/users')->with('message', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $auth = service('authentication');
        $authorize = service('authorization');
        $currentUser = $auth->user();

        if (! $authorize->inGroup(['owner', 'admin'], $currentUser->id)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak punya akses ke menu users.');
        }

        $user = $userModel->where('shop_id', $currentUser->shop_id)->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        if ($user->role === 'owner') {
            return redirect()->to('/users')->with('error', 'Owner tidak boleh dihapus.');
        }

        if ($currentUser->id == $id) {
            return redirect()->to('/users')->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $userModel->delete($id);

        return redirect()->to('/users')->with('message', 'User berhasil dihapus.');
    }

}
