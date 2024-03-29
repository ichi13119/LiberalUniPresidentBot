<?php

namespace App\Console;

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
        Commands\LiberalCommunityRooms::class,
        Commands\InitLiberalCommunityUsers::class,
        Commands\UpdateLiberalCommunityUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //自己紹介未完了 または プロフィール写真未設定 は BOTがリプライ
        $schedule->command('liberal:bot')->everyMinute();
        $schedule->command('liberal:updateLiberalCommunityRooms')->dailyAt('03:00');
        $schedule->command('liberal:updateLiberalCommunityUsers')->dailyAt('04:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
