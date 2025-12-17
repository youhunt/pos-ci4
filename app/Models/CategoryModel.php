<?php

namespace App\Models;

class CategoryModel extends BaseModel
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'shop_id',
        'name',
        'active',
        'slug',
        'created_at',
        'updated_at'
    ];

}
