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
        DB::table('informations')->delete();
        DB::table('detail_informations')->delete();
        DB::table('activities')->delete();
        DB::table('sub_districts')->delete();
        DB::table('housing_estates')->delete();
        DB::table('users')->delete();

        $this->call(UserSeeder::class);
        $this->call(ActivitySeeder::class);

    }
}
