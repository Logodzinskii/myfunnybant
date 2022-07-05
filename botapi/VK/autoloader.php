<?php

function initializeClass($className){

    require_once __DIR__ . '/vkSettings/' . $className . '.php';
}

spl_autoload_register('initializeClass');
