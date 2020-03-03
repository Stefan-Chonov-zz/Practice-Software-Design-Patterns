<?php

namespace Core\Helpers;

use Core\Log;

class SqlHelper
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(env('LOG_PATH'));
    }

    /**
     * @param $parameters
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
     * @param $parameters
     * @return string
     */
    public function prepareAliases($parameters)
    {
        try {
            $result = [];
            foreach ($parameters as $key => $value) {
                $result[$key] = $key . '=:' . $key;
            }

            return join(',', $result);
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}