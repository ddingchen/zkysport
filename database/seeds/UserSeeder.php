<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 5)->create();
        $someone = App\User::first();
        $someone->open_id = 'oVL1qwFi3nd5D2uM4mV6FHeaaEbk';
        $someone->save();

    }
}
