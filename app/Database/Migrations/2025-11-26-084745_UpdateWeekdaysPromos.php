<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWeekdaysPromos extends Migration
{
    public function up()
    {
        // Convert Mon,Tue,Wed,... -> 1,2,3...
        $mapping = [
            'Mon' => 1,
            'Tue' => 2,
            'Wed' => 3,
            'Thu' => 4,
            'Fri' => 5,
            'Sat' => 6,
            'Sun' => 7,
        ];

        $db = \Config\Database::connect();
        $promos = $db->table('promos')->get()->getResult();

        foreach ($promos as $p) {
            if (!$p->weekdays) continue;

            $parts = explode(',', $p->weekdays);
            $nums = [];

            foreach ($parts as $w) {
                $w = trim($w);
                if (isset($mapping[$w])) {
                    $nums[] = $mapping[$w];
                }
            }

            $newValue = implode(',', $nums);

            $db->table('promos')
                ->where('id', $p->id)
                ->update(['weekdays' => $newValue]);
        }
    }

    public function down()
    {
        // Optional: reverse is not needed
    }
}
