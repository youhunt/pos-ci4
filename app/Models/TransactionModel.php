<?php 

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends BaseModel
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'shop_id','invoice','user_id','total_amount','total_paid','discount_amount',
        'payment_status','local_id','created_at','updated_at','synced_at'
    ];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
