<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendLogInfoVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:VisitorCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count visitors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $visitors = DB::table('Visitors')
            ->select('ip')
            ->groupBy('ip')
            ->get();

        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = 'Всего посетителей local: ' . $visitors->count();
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

        return Command::SUCCESS;
    }
}
