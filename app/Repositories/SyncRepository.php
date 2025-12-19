<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseBuilder;

class SyncRepository
{
    /**
     * Normalisasi parameter since
     */
    protected function normalizeSince(?string $since): ?string
    {
        if (!$since) {
            return null;
        }

        // validasi sederhana
        $time = strtotime($since);
        if ($time === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Apply incremental sync condition
     */
    protected function applySince(
        BaseBuilder $builder,
        ?string $since,
        string $column = 'updated_at'
    ): BaseBuilder {
        $since = $this->normalizeSince($since);

        if ($since) {
            $builder->where($column . ' >', $since);
        }

        return $builder;
    }

    /**
     * Apply limit (safety)
     */
    protected function applyLimit(
        BaseBuilder $builder,
        int $limit,
        int $max = 1000
    ): BaseBuilder {
        $limit = max(1, min($limit, $max));
        return $builder->limit($limit);
    }
}
