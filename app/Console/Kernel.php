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
        $schedule->call(function (){

            $visitors = DB::table('Visitors')
                ->select('ip')
                ->groupBy('ip')
                ->get();

            $chatId = config('telegram.TELEGRAMADMIN');
            $token = config('telegram.TELEGRAMTOKEN');
            $message = 'Всего посетителей: ' . $visitors->count();
            $response = array(
                'chat_id' => $chatId,
                'text' => $message,
            );

            $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            curl_close($ch);
        })->hourly();
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
