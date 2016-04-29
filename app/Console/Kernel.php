<?php

namespace App\Console;

use App\Activity;
use App\AreaManager;
use App\BookingOrder;
use App\MpAreaManage;
use App\MpBookingManage;
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

        $schedule->call(function () {
            // scan orders
            $orders = BookingOrder::where('cancel', false)->whereNotNull('expired_at')->whereDate('expired_at', '<=', Carbon::now())->get();
            foreach ($orders as $order) {
                // cancel order
                $order->cancel = true;
                $order->expired_at = null;
                $order->save();
                $areaManager = $order->areaManager;
                if ($areaManager) {
                    $mpBookId = $areaManager->mp_area_manager_record_id;
                    // delete mp area manage record
                    $mpBookingManage = MpBookingManage::findById($mpBookId)->first();
                    if ($mpBookingManage) {
                        $areaCode = $mpBookingManage->areaCode;
                        $mpBookingManage->delete();
                        MpAreaManage::findByCode($areaCode)->updateStatus();
                    }
                    // delete area manage record
                    $areaManager->delete();
                }
            }

            // scan area manager
            $areaManagers = AreaManager::whereNull('order_id')->whereDate('expired_at', '<=', Carbon::now())->get();
            foreach ($areaManagers as $areaManager) {
                $mpBookId = $areaManager->mp_area_manager_record_id;
                $mpBookingManage = MpBookingManage::findById($mpBookId)->first();
                if ($mpBookingManage) {
                    $areaCode = $mpBookingManage->areaCode;
                    $mpBookingManage->delete();
                    MpAreaManage::findByCode($areaCode)->updateStatus();
                }
                $areaManager->delete();
            }

        })->everyTenMinutes();
    }
}
