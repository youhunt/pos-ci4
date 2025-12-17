<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\MultiTenantTrait;

class BaseModel extends Model
{
    use MultiTenantTrait;

    // events to auto-fill shop id
    protected $beforeInsert = ['beforeInsertFillShop'];
    protected $beforeUpdate = []; // you can add hooks if needed

    /**
     * Hook wrapper for trait fillShopId()
     */
    protected function beforeInsertFillShop(array $data)
    {
        return $this->fillShopId($data);
    }

    // Override find/findAll/first to auto apply shop filter
    public function find($id = null)
    {
        $this->applyShopFilter();
        // ensure skipShopFilter resets afterwards
        $result = parent::find($id);
        $this->enableShopFilter();
        return $result;
    }

    public function findAll(?int $limit = null, int $offset = 0)
    {
        $this->applyShopFilter();
        $result = parent::findAll($limit, $offset);
        $this->enableShopFilter();
        return $result;
    }

    public function first()
    {
        $this->applyShopFilter();
        $result = parent::first();
        $this->enableShopFilter();
        return $result;
    }

    // override delete/update to enforce shop filter for safety
    public function update($id = null, $data = null): bool
    {
        // ensure target record belongs to current shop
        if ($this->currentShopId !== null) {
            // use builder to add where shop_id before update
            $builder = $this->db->table($this->table);
            $builder->where($this->table . '.' . $this->shopField, $this->currentShopId);
            if ($id !== null) {
                $builder->where($this->primaryKey, $id);
            }
            return (bool)$builder->update($data);
        }

        return parent::update($id, $data);
    }

    public function delete($id = null, $purge = false)
    {
        if ($this->currentShopId !== null) {
            $builder = $this->db->table($this->table);
            $builder->where($this->table . '.' . $this->shopField, $this->currentShopId);
            if ($id !== null) {
                $builder->where($this->primaryKey, $id);
            }
            return (bool)$builder->delete();
        }

        return parent::delete($id, $purge);
    }

    /**
     * Safe raw query helper when shop filter must be applied manually.
     * Example: $this->db->query($this->withShopRaw('SELECT ... FROM products WHERE ...'));
     */
    public function withShopRaw(string $sql)
    {
        // naive replace: developer must ensure placeholder {shop_id} exists
        return str_replace('{shop_id}', (int)$this->currentShopId, $sql);
    }
}
