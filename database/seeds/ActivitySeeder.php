<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\SubDistrict::class, 5)->create();
        factory(App\HousingEstate::class, 20)->create();

        factory(App\Activity::class, 2)->create()->each(function ($activity) {

            $randInformations = factory(App\Information::class, 2)->make();
            $activity->informations()->saveMany($randInformations);
            $randInformations->each(function ($information) {
                $information->detail()->save(factory(App\DetailInformation::class)->make());
            });

        });
    }
}
