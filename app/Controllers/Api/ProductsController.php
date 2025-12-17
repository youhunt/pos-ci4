<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductsController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $shopId = user()->shop_id;

        $data['products'] = $productModel
            ->setShopId($shopId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('products/index', $data);
    }

    public function create()
    {
        return view('products/create');
    }

    public function store()
    {
        $model = new ProductModel();
        $shopId = user()->shop_id;

        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image && $image->isValid()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        $model->insert([
            'shop_id' => $shopId,
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category_id' => $this->request->getPost('category_id'),
            'barcode' => $this->request->getPost('barcode'),
            'price' => $this->request->getPost('price'),
            'wholesale_price' => $this->request->getPost('wholesale_price'),
            'stock' => $this->request->getPost('stock'),
            'image' => $imageName,
        ]);

        return redirect()->to('/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = new ProductModel();
        $shopId = user()->shop_id;

        // HARUS pakai setShopId agar edit tidak bisa buka produk toko lain
        $product = $model->setShopId($shopId)->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan');
        }

        return view('products/edit', ['product' => $product]);
    }

    public function update($id)
    {
        $model = new ProductModel();
        $shopId = user()->shop_id;

        $product = $model->setShopId($shopId)->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan');
        }

        $image = $this->request->getFile('image');
        $imageName = $product['image'];

        if ($image && $image->isValid()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        $model->update($id, [
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category_id' => $this->request->getPost('category_id'),
            'barcode' => $this->request->getPost('barcode'),
            'price' => $this->request->getPost('price'),
            'wholesale_price' => $this->request->getPost('wholesale_price'),
            'stock' => $this->request->getPost('stock'),
            'image' => $imageName
        ]);

        return redirect()->to('/products')->with('success', 'Produk berhasil diperbarui');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $shopId = user()->shop_id;

        $product = $model->setShopId($shopId)->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan');
        }

        $model->delete($id);

        return redirect()->to('/products')->with('success','Produk berhasil dihapus');
    }

    /* ===========================
       IMPORT PRODUK
    ============================ */
    public function importTemplate()
    {
        $csv = "sku,name,barcode,price,stock\n";
        $csv .= "ABC001,Contoh Produk,1234567890,10000,5\n";

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename=\"template_import_produk.csv\"')
            ->setBody($csv);
    }

    public function import()
    {
        $file = $this->request->getFile('file_csv');
        if (! $file->isValid()) {
            return redirect()->back()->with('error','File tidak valid');
        }

        $shopId = user()->shop_id;
        $model = new ProductModel();

        $rows = array_map('str_getcsv', file($file->getTempName()));

        foreach ($rows as $row) {
            if ($row[0] == 'sku') continue;

            $model->insert([
                'shop_id' => $shopId,
                'sku' => $row[0],
                'name' => $row[1],
                'barcode' => $row[2],
                'price' => $row[3],
                'stock' => $row[4],
            ]);
        }

        return redirect()->to('/products')->with('success','Import produk selesai');
    }

}
