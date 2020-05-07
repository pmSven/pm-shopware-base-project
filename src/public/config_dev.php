<?php

$showErrors       = true;

$useTemplateCache = true;
$useHttpCache     = true;
$useCache         = true;

$useES            = false;

$snippetFromDB    = true; // Default
$snippetToDB      = true; // Default
$snippetFromIni   = false;
$snippetToIni     = false;

$csrfProtection   = false;
$enforceSSL       = false;

$db = [
    'db' => [
        'host' => getenv('MYSQL_HOST'),
        'port' => '3306',
        'username' => getenv("MYSQL_USER"),
        'password' => getenv("MYSQL_PASSWORD"),
        'dbname' => getenv("MYSQL_DATABASE"),
    ],
];

$tplSecurity = [
    'template_security' => [
        'php_modifiers' => ['basename', 'pathinfo']
    ],
];

if ($enforceSSL) {
    $_SERVER['HTTPS'] = 'On';
    $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
}

$config = [];

if ($useES) {
// https://developers.shopware.com/developers-guide/shopware-config/#elasticsearch
    $config = array_merge($config, [
        'es' => [
            'enabled' => true,
            'number_of_replicas' => '0',
            'number_of_shards' => null,
            'write_backlog' => false,
            'backend' => [
                'write_backlog' => false,
                'enabled' => false,
            ],
            'client' => [
                'hosts' => [
                    getenv("ES_HOST")
                ]
            ]
        ]
    ]);
}

if ($showErrors) {
    $config = array_merge($config, [
        'phpsettings' => [
            'display_errors' => 1,
            'session.gc_maxlifetime' => 7200,
        ],
        'front' => [
            'throwExceptions' => true,
            'showException' => true,
        ],
    ]);
}

if (!$useTemplateCache) {
    $config = array_merge($config, [
        'template' => [
            'forceCompile' => true,
        ],
    ]);
}

$config = array_merge($config, [
    'httpcache' => [
        'enabled' => $useHttpCache,
        'debug' => true,
    ],
]);

$config = array_merge($config, [
    'snippet' => [
        'readFromDb' => $snippetFromDB,
        'writeToDb' => $snippetToDB,
        'readFromIni' => $snippetFromIni,
        'writeToIni' => $snippetToIni,
    ],
]);

if (!$useCache) {
    $config = array_merge($config, [
        'cache' => [
            'backend' => 'Black-Hole',
            'backendOptions' => [],
            'frontendOptions' => [
                'write_control' => false,
            ],
        ],
        'model' => [
            'cacheProvider' => 'Array',
        ],
    ]);
}

if (!$csrfProtection) {
    $config = array_merge($config, [
        'csrfProtection' => [
            'frontend' => false,
            'backend' => false
        ],
    ]);
}

$all = array_merge($db, $config);

return $all;
