<?php

namespace App\Models;

class ProductModel extends BaseModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'shop_id','sku','name','description','category_id','barcode',
        'price','wholesale_price','image','has_variants','stock',
        'created_at','updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
