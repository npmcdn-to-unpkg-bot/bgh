<?php

return [

    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION', 'mysql_ar'),

    'connections' => [

        'mysql_ar' => [
            'driver'    => 'mysql',
            'host'      => env('DB_AR_HOST', 'localhost'),
            'database'  => env('DB_AR_DATABASE', 'forge'),
            'username'  => env('DB_AR_USERNAME', 'forge'),
            'password'  => env('DB_AR_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'mysql_uy' => [
            'driver'    => 'mysql',
            'host'      => env('DB_UY_HOST', 'localhost'),
            'database'  => env('DB_UY_DATABASE', 'forge'),
            'username'  => env('DB_UY_USERNAME', 'forge'),
            'password'  => env('DB_UY_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],


    ],

    'migrations' => 'migrations',

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];
