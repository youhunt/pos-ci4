<?php

namespace App\Services;

use App\Models\PromoModel;
use App\Models\PromoProductModel;

class PromoService extends BaseService
{
    public function getActiveProductPromos(int $shopId): array
    {
        $promoModel = new PromoModel();
        $promoProductModel = new PromoProductModel();

        $promos = $promoModel->getActivePromosForPOS($shopId);

        $result = [];

        foreach ($promos as $promo) {
            if ($promo['type'] !== 'product') continue;

            $products = $promoProductModel
                ->where('promo_id', $promo['id'])
                ->findAll();

            foreach ($products as $pp) {
                $result[$pp['product_id']] = [
                    'promo_id' => $promo['id'],
                    'type'     => $promo['discount_type'],
                    'value'    => (float)$promo['discount_value'],
                ];
            }
        }

        return $result;
    }
}
