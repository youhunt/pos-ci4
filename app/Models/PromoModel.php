<?php

namespace App\Models;

class PromoModel extends BaseModel
{
    protected $table = 'promos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'shop_id','name','type','discount_type','discount_value',
        'category_id','product_id','start_date','end_date',
        'start_time','end_time','weekdays','active','created_at','updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;

    // Ambil promo aktif untuk produk tertentu
    public function getActivePromos($shopId, $productId, $categoryId)
    {
        $now = date('Y-m-d H:i:s');
        $weekday = date('N'); // 1..7

        $builder = $this->db->table('promos p');

        // LEFT JOIN ke promo_products karena promo tipe 'product' bisa menyimpan banyak product di sana
        $builder->join('promo_products pp', 'pp.promo_id = p.id', 'left');

        // Basic filters
        $builder->where('p.shop_id', $shopId);
        $builder->where('p.active', 1);

        // MATCH BERDASARKAN TYPE
        // - global: tipe global (kondisi explicit)
        // - category: jenis category dan category_id cocok
        // - product: jenis product dan cek p.product_id OR pp.product_id
        $builder->groupStart()
            ->where("p.type = 'global' AND p.product_id IS NULL AND p.category_id IS NULL", null, false)
            ->orWhere("p.type = 'category' AND p.category_id = {$categoryId}", null, false)
            // product: either stored in promos.product_id OR in promo_products (pp)
            ->orWhere("p.type = 'product' AND pp.product_id = {$productId}", null, false)
        ->groupEnd();

        // tanggal validitas
        $builder->where("(p.start_date IS NULL OR p.start_date <= '{$now}')", null, false);
        $builder->where("(p.end_date IS NULL OR p.end_date >= '{$now}')", null, false);

        // weekdays (disimpan numeric CSV like "1,2,3")
        $builder->where("(p.weekdays IS NULL OR FIND_IN_SET({$weekday}, p.weekdays))", null, false);

        // waktu validitas (jam)
        $builder->where("(p.start_time IS NULL OR p.start_time <= CURTIME())", null, false);
        $builder->where("(p.end_time IS NULL OR p.end_time >= CURTIME())", null, false);

        // Pastikan satu row per promo (hindari duplikasi karena JOIN)
        $builder->groupBy('p.id');

        $result = $builder->get()->getResultArray();

        // debug
        log_message('debug', 'PROMO SQL: ' . $this->db->getLastQuery());
        log_message('debug', 'PROMOS FOUND: ' . count($result));

        return $result;
    }

    public function getActivePromosForPOS(int $shopId): array
    {
        $now = date('Y-m-d H:i:s');
        $weekday = date('N');

        $builder = $this->db->table('promos p')
            ->select('p.*')
            ->where('p.shop_id', $shopId)
            ->where('p.active', 1)

            ->where("(p.start_date IS NULL OR p.start_date <= CURDATE())", null, false)
            ->where("(p.end_date IS NULL OR p.end_date >= CURDATE())", null, false)

            ->where("(p.weekdays IS NULL OR FIND_IN_SET({$weekday}, p.weekdays))", null, false)
            ->where("(p.start_time IS NULL OR p.start_time <= CURTIME())", null, false)
            ->where("(p.end_time IS NULL OR p.end_time >= CURTIME())", null, false)

            ->orderBy('p.updated_at', 'ASC');

        $result = $builder->get()->getResultArray();

        // debug
        log_message('debug', 'PROMO SQL: ' . $this->db->getLastQuery());
        log_message('debug', 'PROMOS FOUND: ' . count($result));
        return $result;
    }

    public function getAllActivePromosForPOS(int $shopId): array
    {
        $now = date('Y-m-d H:i:s');
        $weekday = date('N');

        $builder = $this->db->table('promos p')
            ->select('p.*')
            ->where('p.shop_id', $shopId)
            ->where('p.active', 1)

            ->where("(p.start_date IS NULL OR p.start_date <= CURDATE())", null, false)
            ->where("(p.end_date IS NULL OR p.end_date >= CURDATE())", null, false)

            ->where("(p.weekdays IS NULL OR FIND_IN_SET({$weekday}, p.weekdays))", null, false)
            ->where("(p.start_time IS NULL OR p.start_time <= CURTIME())", null, false)
            ->where("(p.end_time IS NULL OR p.end_time >= CURTIME())", null, false)

            ->orderBy('p.updated_at', 'ASC');

        return $builder->get()->getResultArray();
    }
}
