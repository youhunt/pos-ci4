<?php

namespace App\Controllers\Api;

use App\Models\ShopModel;
use Myth\Auth\Models\UserModel;

class ShopController extends BaseController
{
    public function register()
    {
        return view('shop/register');
    }

    public function saveRegister()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'address' => 'permit_empty',
            'logo' => 'permit_empty|uploaded[logo]|max_size[logo,2048]|is_image[logo]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $shopModel = new ShopModel();

        // Slug otomatis
        helper('text');
        $slug = url_title($this->request->getPost('name'), '-', true);

        // Upload logo (optional)
        $logoName = null;
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid()) {
            $logoName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/logo/', $logoName);
        }

        // Simpan toko
        $shopId = $shopModel->insert([
            'name' => $this->request->getPost('name'),
            'slug' => $slug,
            'address' => $this->request->getPost('address'),
            'logo' => $logoName,
        ]);

        // Update user → mengikat ke toko baru
        $user = user(); // dari Myth-Auth
        $userModel = new UserModel();
        $userModel->update($user->id, ['shop_id' => $shopId]);

        return redirect()->to('/dashboard')->with('message', 'Toko berhasil dibuat!');
    }
}
