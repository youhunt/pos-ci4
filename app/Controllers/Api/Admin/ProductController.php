<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $product;

    public function __construct()
    {
        $this->product = new ProductModel();
    }

    // GET /api/admin/products
    public function index()
    {
        $shopId = (int)$this->request->getGet('shop_id');
        $q      = $this->request->getGet('q');

        $builder = $this->product
            ->where('shop_id', $shopId);

        if ($q) {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
                ->orLike('barcode', $q)
            ->groupEnd();
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'data'   => $builder->orderBy('name')->findAll()
        ]);
    }

    // POST /api/admin/products
    public function store()
    {
        $data = $this->request->getJSON(true);

        $this->product->insert([
            'shop_id' => $data['shop_id'],
            'name'    => $data['name'],
            'sku'     => $data['sku'] ?? null,
            'barcode' => $data['barcode'] ?? null,
            'price'   => $data['price'],
            'is_active' => 1
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    // PUT /api/admin/products/{id}
    public function update($id)
    {
        $data = $this->request->getJSON(true);

        $this->product->update($id, [
            'name'    => $data['name'],
            'sku'     => $data['sku'] ?? null,
            'barcode' => $data['barcode'] ?? null,
            'price'   => $data['price'],
            'is_active' => $data['is_active']
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }
}
