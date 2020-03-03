<?php

namespace Core;

class DB
{
    protected static $instance;

    public static function getInstance() {
        try {
            if (is_null(self::$instance)) {
                $dsn = 'mysql:host=' . env('DB_HOST') . ':' . env('DB_PORT') . ';dbname=' . env('DB_NAME');
                self::$instance = new \PDO($dsn, env('DB_USER'), env('DB_PASS'));
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
        } catch(\PDOException $exception) {
            throw $exception;
        }

        return self::$instance;
    }
}