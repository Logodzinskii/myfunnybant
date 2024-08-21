<?php

namespace App\Console;

use App\Models\Visitors;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:VisitorCount')->dailyAt('13:00')->withoutOverlapping(3);
        $schedule->command('app:ActionsOzon')->dailyAt('13:30')->withoutOverlapping(3);
        //$schedule->command('app:OzonChatList')->everySixHours();
        //$schedule->command('app:analyticOzonReport')->hourly();
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
