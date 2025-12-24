<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\PromoProductModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Services\PromoService;
use CodeIgniter\API\ResponseTrait;

class PromoController extends BaseController
{
    use ResponseTrait;

    protected $promoModel;
    protected $promoProductModel;
    protected $productModel;
    protected $categoryModel;
    protected $promoService;

    public function __construct()
    {
        $this->promoModel        = new PromoModel();
        $this->promoProductModel = new PromoProductModel();
        $this->productModel      = new ProductModel();
        $this->categoryModel     = new CategoryModel();
        $this->promoService      = new PromoService();
    }

    public function getActive()
    {
        $shopId = (int)$this->request->getGet('shop_id');

        if (!$shopId) {
            return $this->fail('shop_id required');
        }

        return $this->respond(
            $this->promoService->getActiveProductPromos($shopId)
        );

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
