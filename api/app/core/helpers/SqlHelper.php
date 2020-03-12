<?php

namespace App\Core\Helpers;

use App\Core\Log;

class SqlHelper
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(env('LOG_PATH'));
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function prepareParameters($parameters)
    {
        try {
            $result = [];
            foreach ($parameters as $key => $value) {
                $result[':' . $key] = $value;
            }

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param array $parameters
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public function prepareAliases($parameters, $prefix = '', $suffix = '')
    {
        try {
            $result = [];
            $index = 0 ;
            foreach ($parameters as $key => $value) {
                $result[$key] = '';
                if (!empty($prefix) && $index < count($parameters) - 1) {
                    $result[$key] .= $prefix;
                }

                if ($key == 'key' || $key == 'id') {
                    $result[$key] .= '`' . $key . '` = :' . $key;
                } else {
                    $result[$key] .= $key . ' = :' . $key;
                }

                if (!empty($suffix) && $index < count($parameters) - 1) {
                    $result[$key] .= $suffix;
                }
                $index++;
            }

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}