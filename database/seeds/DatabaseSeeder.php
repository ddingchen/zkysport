<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->delete();
        // DB::table('informations')->delete();
        // DB::table('sellers')->delete();
        // DB::table('sell_productions')->delete();
        // DB::table('activities')->delete();

        // $this->call(UserSeeder::class);
        $this->call(ActivitySeeder::class);
        // $this->call(ProductionSeeder::class);

    }
}
