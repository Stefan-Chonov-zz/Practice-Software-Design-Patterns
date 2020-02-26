<?php

namespace Core;

define("DEFAULT_INFO_LOG_PATH", __DIR__ . '/log/info.log');
define("DEFAULT_WARNING_LOG_PATH", __DIR__ . '/log/warning.log');
define("DEFAULT_ERROR_LOG_PATH", __DIR__ . '/log/error.log');

class Log
{
    /**
     * Save the info to a log
     * @param $stringMessage - Message to write in a log
     */
    public static function info($stringMessage)
    {
        $infoLogPath = (!empty(env('INFO_LOG_PATH')) ? env('INFO_LOG_PATH') : DEFAULT_INFO_LOG_PATH);
        self::saveToLog($stringMessage, $infoLogPath);
    }

    /**
     * Save the warning to a log
     * @param $stringMessage - Message to write in a log
     */
    public static function warning($stringMessage)
    {
        $warningLogPath = (!empty(env('WARNING_LOG_PATH')) ? env('WARNING_LOG_PATH') : DEFAULT_WARNING_LOG_PATH);
        self::saveToLog($stringMessage, $warningLogPath);
    }

    /**
     * Save the error to a log
     * @param $stringMessage - Message to write in a log
     */
    public static function error($stringMessage)
    {
        $errorLogPath = (!empty(env('ERROR_LOG_PATH')) ? env('ERROR_LOG_PATH') : DEFAULT_ERROR_LOG_PATH);
        self::saveToLog($stringMessage, $errorLogPath);
    }

    /**
     * Write message to a log
     * @param $stringMessage - Message to write in a log
     * @param $logFilePath - Path to log file
     */
    private static function saveToLog($stringMessage, $logFilePath)
    {
        if(!file_exists($logFilePath)) {
            if (!file_exists(dirname($logFilePath)) && !mkdir(dirname($logFilePath), 0777, true)) {
                die('Failed to create folders...');
            }

            file_put_contents($logFilePath, '');
        }

        $stringMessage = '[' . date("Y-m-d H:i:s") . '] ' . $stringMessage . PHP_EOL;
        error_log($stringMessage, 3, $logFilePath);
    }
}