<?php

/*
    |--------------------------------------------------------------------------
    | Ozon Connections
    |--------------------------------------------------------------------------
    |
    | файл хранит в себе настройки токена и ключа ozon myfunnybant
    |
    */

use Illuminate\Support\Str;

return [

        'TELEGRAMTOKEN'=>env('TELEGRAM_TOKEN'),
        'TELEGRAMADMIN'=>env('TELEGRAM_ADMIN'),
        'TELEGRAMMANAGER'=>env('TELEGRAM_MANAGER'),
];
