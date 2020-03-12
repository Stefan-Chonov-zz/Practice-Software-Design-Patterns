<?php

namespace App\Core\Helpers;

use App\Core\Log;

class ClassName
{
    public static function getShortName($obj)
    {
        try {
            $reflect = new \ReflectionClass($obj);
            return $reflect->getShortName();
        } catch (\ReflectionException $ex) {
            $log = new Log(env('LOG_PATH'));
            $log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}