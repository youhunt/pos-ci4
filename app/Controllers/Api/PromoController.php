<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\PromoProductModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use CodeIgniter\API\ResponseTrait;

class PromoController extends BaseController
{
    use ResponseTrait;

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

    public function getActive()
    {
        $shopId = (int)$this->request->getGet('shop_id');

        if (!$shopId) {
            return $this->fail('shop_id required');
        }

        $rawPromos = $this->promoModel->getAllActivePromosForPOS($shopId);

        $promos = [];

        foreach ($rawPromos as $p) {
            if ($p['type'] === 'product') {
                $products = $this->promoProductModel
                    ->where('promo_id', $p['id'])
                    ->findAll();

                foreach ($products as $pp) {
                    $row = $p;
                    $row['product_id'] = $pp['product_id'];
                    $promos[] = $row;
                }
            } else {
                $promos[] = $p;
            }
        }

        return $this->respond(['data' => $promos]);

    }


    public function getDiscountedProducts()
    {
        $shopId = (int)$this->request->getGet('shop_id');

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
