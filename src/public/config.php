<?php return [
    'db' =>
        [
            'host' => getenv('MYSQL_HOST'),
            'port' => '3306',
            'username' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'dbname' => getenv('MYSQL_DATABASE'),
        ],
    'csrfProtection' => [
        'frontend' => false,
        'backend' => false
    ],
    'phpsettings' => [
        'display_errors' => 1,
        'session.gc_maxlifetime' => 7200
    ],
    'front' => [
        'throwExceptions' => true,
    ],
    'template' => [
        'forceCompile' => true,
    ],
    'httpcache' => [
        'enabled' => false,
        'debug' => true,
    ],
    'cache' => [
        'backend' => 'Black-Hole'
    ],
    'model' => [
        'cacheProvider' => 'Array'
    ],
];
