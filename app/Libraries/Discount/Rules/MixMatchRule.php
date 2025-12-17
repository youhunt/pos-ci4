<?php

namespace App\Libraries\Discount\Rules;

class MixMatchRule
{
    public function apply(array $items, $shopId)
    {
        // Example MixMatch simple rule:
        // If total qty >= 3 across certain categories → discount 10,000
        $discount = 0;
        $totalQty = array_sum(array_column($items, 'qty'));

        if ($totalQty >= 3) {
            $discount = 10000;
        }

        return [
            'items' => $items,
            'discount' => $discount
        ];
    }
}
