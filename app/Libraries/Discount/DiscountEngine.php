<?php

namespace App\Libraries\Discount;

use App\Libraries\Discount\Rules\PercentageRule;
use App\Libraries\Discount\Rules\BuyXGetYRule;
use App\Libraries\Discount\Rules\MixMatchRule;

class DiscountEngine
{
    protected $rules = [];

    public function __construct()
    {
        // Register available promo rules
        $this->rules = [
            new PercentageRule(),
            new BuyXGetYRule(),
            new MixMatchRule(),
        ];
    }

    /**
     * Apply promo rules to items
     */
    public function apply(array $items, $shopId)
    {
        $totalDiscount = 0;

        foreach ($this->rules as $rule) {
            $result = $rule->apply($items, $shopId);
            $items  = $result['items'];
            $totalDiscount += $result['discount'];
        }

        return [
            'items_after_discount' => $items,
            'total_discount'       => $totalDiscount
        ];
    }
}
