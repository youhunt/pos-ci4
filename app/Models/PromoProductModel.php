<?php

namespace App\Models;

class PromoProductModel extends BaseModel
{
    protected $table = 'promo_products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'shop_id', 'promo_id', 'product_id'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
