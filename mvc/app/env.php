<?php

$variables = [
    // App
    'APP_NAME' => 'MVC_SINGLETON_REPOSITORY',
    'APP_PATH' => __DIR__,
    'APP_URL' => 'http://localhost',

    // MySql
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_NAME' => 'cwb',
    'DB_USER' => 'root',
    'DB_PASS' => '',

    // Logs
    'ERROR_LOG_PATH' => __DIR__ . "/log/error.log",
    'INFO_LOG_PATH' => __DIR__ . "/log/admin.log",
    'WARNING_LOG_PATH' => __DIR__ . "/log/warning.log",

    // Views
    'VIEW_PATH' => __DIR__ . "/view/",
    'PAGE_NOT_FOUND' => __DIR__ . "/view/pageNotFound.php",
];

foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
?>