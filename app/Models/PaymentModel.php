<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model {
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['transaction_id','method','amount', 'note','created_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
