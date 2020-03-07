<?php

$variables = [
    // App
    'APP_NAME' => 'MVC_SINGLETON_REPOSITORY',
    'APP_PATH' => __DIR__,
    'APP_URL' => 'http://localhost',

    // MySql
    'DB_MYSQL_HOST' => 'localhost',
    'DB_MYSQL_PORT' => '3306',
    'DB_MYSQL_NAME' => 'cwb',
    'DB_MYSQL_USER' => 'root',
    'DB_MYSQL_PASS' => '',

    // PostreSql
    'DB_PGSQL_HOST' => 'localhost',
    'DB_PGSQL_PORT' => '5432',
    'DB_PGSQL_NAME' => 'cwb',
    'DB_PGSQL_USER' => 'root',
    'DB_PGSQL_PASS' => '',

    // Logs
    'LOG_PATH' => __DIR__ . "/log/log.log",

    // Views
    'VIEW_PATH' => __DIR__ . "/view/",
    'PAGE_NOT_FOUND' => __DIR__ . "/view/pageNotFound.php",
];

foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
?>