<?php

require_once 'Configuration/TelegramApiConfiguration.php';
require_once 'Configuration/DateBase.php';
require_once 'Configuration/UserConfiguration.php';

function initializeClass($className){

    require_once __DIR__ . '/Classes/' . $className . '.php';
}

spl_autoload_register('initializeClass');