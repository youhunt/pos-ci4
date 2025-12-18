<?php 

namespace App\Models;

use CodeIgniter\Model;

class TransactionItemModel extends Model
{
    protected $table = 'transaction_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['transaction_id','product_id','variant_id','qty','price','subtotal','created_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;

    public function getItemsWithProduct(int $trxId): array
    {
        return $this->select([
                'transaction_items.*',
                'products.name'
            ])
            ->join('products', 'products.id = transaction_items.product_id', 'left')
            ->where('transaction_items.transaction_id', $trxId)
            ->findAll();
    }

}
