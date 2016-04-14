<?php

use App\SellProduction;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellProduction::create([
            'title' => '500亲子套餐',
            'description' => '500亲子套餐500亲子套餐500亲子套餐',
        ]);
        SellProduction::create([
            'title' => '1500亲子套餐',
            'description' => '500亲子套餐500亲子套餐500亲子套餐',
        ]);
        SellProduction::create([
            'title' => '5000亲子套餐',
            'description' => '500亲子套餐500亲子套餐500亲子套餐',
        ]);
    }
}
