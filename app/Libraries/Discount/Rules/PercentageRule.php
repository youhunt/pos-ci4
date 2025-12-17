<?php

namespace App\Libraries\Discount\Rules;

class PercentageRule
{
    public function apply(array $items, $shopId)
    {
        $discount = 0;

        foreach ($items as &$row) {

            if (isset($row['product']['discount_percent']) && $row['product']['discount_percent'] > 0) {

                $d = ($row['line_total'] * $row['product']['discount_percent']) / 100;
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
