<?php

class LogWiriter
{
    public function writeLog($message)
    {
        $dat = date('Y-m-d h:i:s');
        file_put_contents('log.txt',$dat . '-'. $message.PHP_EOL, FILE_APPEND);
    }
}