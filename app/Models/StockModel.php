<?php 

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends BaseModel
{
    protected $table = 'stocks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['shop_id','product_id','variant_id','type','qty','note','created_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
