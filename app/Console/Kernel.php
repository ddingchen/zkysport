<?php

namespace App\Console;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // set boolean if activity expired
        $schedule->call(function () {
            $now = Carbon::now();
            foreach (Activity::all() as $activity) {
                $activity->published = false;
                if ($activity->start_from->lte($now)) {
                    $activity->published = true;
                }
                $activity->expired = false;
                if ($activity->end_to->lt($now)) {
                    $activity->expired = true;
                }
                $activity->save();
            }
        })->dailyAt('00:00');
    }
}
