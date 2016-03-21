<?php

use App\BookingAreaSelect;
use App\BookingTimeSelect;
use App\Sport;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $badminton = Sport::create([
            // 'id' => 1,
            'name' => '羽毛球',
            'require_number_of_people' => false,
            'area_choose_type' => 'chart',
            'booking_time_type' => 'hourly',
        ]);
        BookingTimeSelect::create([
            'sport_id' => $badminton->id,
            'start_from' => '09:00',
            'end_from' => '17:00',
            'sort' => 1,
        ]);
        BookingTimeSelect::create([
            'sport_id' => $badminton->id,
            'start_from' => '17:00',
            'end_from' => '22:00',
            'sort' => 2,
        ]);
        foreach (range(1, 14) as $num) {
            BookingAreaSelect::create([
                'sport_id' => $badminton->id,
                'code' => $num,
                'title' => $num . '号',
                'sort' => $num,
            ]);
        }
        $pingpong = Sport::create([
            // 'id' => 2,
            'name' => '乒乓球',
            'require_number_of_people' => true,
            'area_choose_type' => 'chart',
            'booking_time_type' => 'hourly',
        ]);
        BookingTimeSelect::create([
            'sport_id' => $pingpong->id,
            'start_from' => '09:00',
            'end_from' => '17:00',
            'sort' => 1,
        ]);
        BookingTimeSelect::create([
            'sport_id' => $pingpong->id,
            'start_from' => '17:00',
            'end_from' => '22:00',
            'sort' => 2,
        ]);
        foreach (range(1, 6) as $num) {
            BookingAreaSelect::create([
                'sport_id' => $pingpong->id,
                'code' => $num,
                'title' => $num . '号',
                'sort' => $num,
            ]);
        }
        $basketball = Sport::create([
            // 'id' => 3,
            'name' => '篮球',
            'require_number_of_people' => false,
            'area_choose_type' => 'list',
            'booking_time_type' => 'none',
        ]);
        BookingAreaSelect::create([
            'sport_id' => $basketball->id,
            'code' => 1,
            'title' => '半场',
            'sort' => 1,
        ]);
        BookingAreaSelect::create([
            'sport_id' => $basketball->id,
            'code' => 2,
            'title' => '全场',
            'sort' => 2,
        ]);

        // $football = Sport::create([
        //     'name' => '足球',
        //     'require_number_of_people' => false,
        //     'area_choose_type' => 'list',
        //     'booking_time_type' => 'period',
        // ]);
        // BookingTimeSelect::create([
        //     'sport_id' => $football->id,
        //     'start_from' => '18:00',
        //     'end_from' => '20:00',
        //     'sort' => 1,
        // ]);
        // BookingTimeSelect::create([
        //     'sport_id' => $football->id,
        //     'start_from' => '20:00',
        //     'end_from' => '22:00',
        //     'sort' => 2,
        // ]);

    }
}
