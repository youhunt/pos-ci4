<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductVariantModel extends BaseModel
{
    protected $table = 'product_variants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id','shop_id','sku','name','price','stock','created_at','updated_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
