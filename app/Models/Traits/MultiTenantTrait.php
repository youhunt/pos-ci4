<?php

namespace App\Models\Traits;

trait MultiTenantTrait
{
    protected $shopField = 'shop_id';
    protected $currentShopId = null;
    protected $autoFillShopOnCreate = true; // set false jika tidak mau auto-fill
    protected $autoFillUserOnCreate = false; // optional to fill created_by

    // allow temporarily disabling the shop filter (use with caution)
    protected $skipShopFilter = false;

    /**
     * Set current shop id for filtering (fluently).
     */
    public function setShopId($shopId)
    {
        $this->currentShopId = $shopId;
        return $this;
    }

    /**
     * Disable the shop filter for the next query (use for admin cross-shop queries)
     */
    public function withoutShopFilter()
    {
        $this->skipShopFilter = true;
        return $this;
    }

    /**
     * Re-enable the shop filter (internal).
     */
    protected function enableShopFilter()
    {
        $this->skipShopFilter = false;
    }

    /**
     * Apply shop filter to query builder if set.
     * Call this before any read operation (find, findAll, first).
     */
    protected function applyShopFilter()
    {
        if ($this->skipShopFilter) {
            // leave skip enabled only for this request; caller should chain reset
            return;
        }

        if ($this->currentShopId !== null) {
            // ensure full column name to avoid ambiguous column in joins
            $col = $this->table . '.' . $this->shopField;
            $this->where($col, $this->currentShopId);
        }
    }

    /**
     * Auto-fill shop_id before insert.
     * Called from BaseModel::beforeInsertCallback
     */
    protected function fillShopId(array $data)
    {
        if (! $this->autoFillShopOnCreate) return $data;

        // if shop id already present in payload, respect it
        if (isset($data['data'][$this->shopField]) && ! empty($data['data'][$this->shopField])) {
            return $data;
        }

        // try pick from currentShopId or from user() if available
        if ($this->currentShopId !== null) {
            $data['data'][$this->shopField] = $this->currentShopId;
            return $data;
        }

        // try to detect auth user (if app provides helper)
        if (function_exists('user') && ($u = user()) && isset($u->shop_id)) {
            $data['data'][$this->shopField] = $u->shop_id;
            return $data;
        }

        return $data;
    }
}
