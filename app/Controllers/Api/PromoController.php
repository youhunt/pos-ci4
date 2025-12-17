<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\PromoProductModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class PromoController extends BaseController
{
    protected $promoModel;
    protected $promoProductModel;
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->promoModel        = new PromoModel();
        $this->promoProductModel = new PromoProductModel();
        $this->productModel      = new ProductModel();
        $this->categoryModel     = new CategoryModel();
    }

    /** ------------------------------
     * LIST PROMO
     * ------------------------------ */
    public function index()
    {
        $shopId = user()->shop_id;

        $promos = $this->promoModel
            ->where('shop_id', $shopId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('promo/index', [
            'promos' => $promos
        ]);
    }

    /** ------------------------------
     * CREATE FORM
     * ------------------------------ */
    public function create()
    {
        $shopId = user()->shop_id;

        return view('promo/create', [
            'products'   => $this->productModel->where('shop_id', $shopId)->orderBy('name')->findAll(),
            'categories' => $this->categoryModel->where('shop_id', $shopId)->orderBy('name')->findAll(),
        ]);
    }

    /** ------------------------------
     * STORE NEW PROMO
     * ------------------------------ */
    public function store()
    {
        $shopId = user()->shop_id;

        $data = [
            'shop_id'        => $shopId,
            'name'           => $this->request->getPost('name'),
            'type'           => $this->request->getPost('type'),
            'category_id'    => $this->request->getPost('category_id'),
            'discount_type'  => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'start_time'     => $this->request->getPost('start_time'),
            'end_time'       => $this->request->getPost('end_time'),
            'weekdays'       => implode(',', (array)$this->request->getPost('weekdays')),
            'active'         => $this->request->getPost('active') ? 1 : 0,
        ];

        $promoId = $this->promoModel->insert($data);

        // Save promo products (if type = product)
        $products = $this->request->getPost('products');
        if (!empty($products)) {
            foreach ($products as $pid) {
                $this->promoProductModel->insert([
                    'promo_id'   => $promoId,
                    'product_id' => $pid,
                    'shop_id'    => $shopId,
                ]);
            }
        }

        return redirect()->to('/promo')->with('success', 'Promo berhasil disimpan');
    }

    /** ------------------------------
     * EDIT FORM
     * ------------------------------ */
    public function edit($id)
    {
        $shopId = user()->shop_id;

        $promo = $this->promoModel
            ->where('shop_id', $shopId)
            ->find($id);

        if (!$promo) return redirect()->back()->with('error', 'Promo tidak ditemukan');

        return view('promo/create', [
            'promo'         => $promo,
            'products'      => $this->productModel->where('shop_id', $shopId)->orderBy('name')->findAll(),
            'categories'    => $this->categoryModel->where('shop_id', $shopId)->orderBy('name')->findAll(),
            'promoProducts' => $this->promoProductModel
                ->where('promo_id', $id)
                ->where('shop_id', $shopId)
                ->findAll(),
        ]);
    }

    /** ------------------------------
     * UPDATE PROMO
     * ------------------------------ */
    public function update($id)
    {
        $shopId = user()->shop_id;

        $data = [
            'name'           => $this->request->getPost('name'),
            'type'           => $this->request->getPost('type'),
            'category_id'    => $this->request->getPost('category_id'),
            'discount_type'  => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'start_time'     => $this->request->getPost('start_time'),
            'end_time'       => $this->request->getPost('end_time'),
            'weekdays'       => implode(',', (array)$this->request->getPost('weekdays')),
            'active'         => $this->request->getPost('active') ? 1 : 0,
        ];

        $this->promoModel
            ->where('shop_id', $shopId)
            ->update($id, $data);

        /** Refresh promo_products */
        $this->promoProductModel
            ->where('promo_id', $id)
            ->where('shop_id', $shopId)
            ->delete();

        $products = $this->request->getPost('products');
        if (!empty($products)) {
            foreach ($products as $pid) {
                $this->promoProductModel->insert([
                    'promo_id'   => $id,
                    'product_id' => $pid,
                    'shop_id'    => $shopId,
                ]);
            }
        }

        return redirect()->to('/promo')->with('success', 'Promo berhasil diupdate');
    }

    /** ------------------------------
     * DELETE PROMO
     * ------------------------------ */
    public function delete($id)
    {
        $shopId = user()->shop_id;

        $this->promoProductModel
            ->where('promo_id', $id)
            ->where('shop_id', $shopId)
            ->delete();

        $this->promoModel
            ->where('shop_id', $shopId)
            ->delete($id);

        return redirect()->to('/promo')->with('success', 'Promo berhasil dihapus');
    }

    public function getActive()
    {
        $shopId = user()->shop_id;

        $promoModel = new PromoModel();
        $promos = $promoModel->getAllActivePromos($shopId);

        return $this->response->setJSON($promos);
    }

    public function getDiscountedProducts()
    {
        $shopId = user()->shop_id;

        $promoModel   = new PromoModel();
        $productModel = new ProductModel();

        $promos = $promoModel->getAllActivePromos($shopId);
        $productIds = [];

        foreach ($promos as $p) {
            if ($p['product_id']) {
                $productIds[] = $p['product_id'];
            }
        }

        $products = [];
        if (count($productIds)) {
            $products = $productModel
                ->whereIn('id', $productIds)
                ->findAll();
        }

        return $this->response->setJSON($products);
    }
}
