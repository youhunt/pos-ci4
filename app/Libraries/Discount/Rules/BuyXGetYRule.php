<?php

namespace App\Libraries\Discount\Rules;

class BuyXGetYRule
{
    public function apply(array $items, $shopId)
    {
        // Example: if qty >= 2 → get discount 1 item
        $discount = 0;

        foreach ($items as &$row) {
            if ($row['qty'] >= 2) {
                $d = $row['price'];
                $row['discount'] = ($row['discount'] ?? 0) + $d;
                $discount += $d;
            }
        }

        return [
            'items' => $items,
            'discount' => $discount
        ];
    }
}
