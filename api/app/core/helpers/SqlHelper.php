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
     * @return string
     */
    public function prepareAliases($parameters)
    {
        try {
            $result = [];
            foreach ($parameters as $key => $value) {
                $result[$key] = $key . ' = :' . $key;
            }

            return join(',', $result);
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function whereAnd($parameters)
    {
        try {
            $result = '';
            $index = 0 ;
            foreach ($parameters as $key => $value) {
                $result .= $key . ' = :' . $key;
                if ($index < count($parameters) - 1) {
                    $result .= ' AND ';
                }
                $index++;
            }

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function whereOr($parameters)
    {
        try {
            $result = '';
            $index = 0 ;
            foreach ($parameters as $key => $value) {
                $result .= $key . ' = :' . $key;
                if ($index < count($parameters) - 1) {
                    $result .= ' OR ';
                }
                $index++;
            }

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}