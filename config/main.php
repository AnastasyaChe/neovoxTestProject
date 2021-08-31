<?php

return [
    'root_dir' => realpath(__DIR__ . '/../') . "/",
    'views_dir' => realpath(__DIR__ . '/../') . "/views/",
    'vendor_dir' => realpath(__DIR__ . '/../') . "/vendor/",
    'default_controller' => 'users',
    'default_page' => '1',
    'controller_namespace' => 'app\controllers\\',
    'components' => [
        'request' => [
            'class' => \app\base\Request::class,
        ],
        'session' => [
            'class' => \app\base\Session::class,
        ],
        'renderer' => [
            'class' => \app\services\renderers\TemplateRenderer::class,
        ],
        'db' => [
            'class' => \app\services\Db::class,
            'driver' => 'mysql',
            'host' => 'localhost',
            'login' => 'root',
            'password' => 'root',
            'database' => 'guest_book',
            'charset' => 'utf8'
        ],
        'user' => [
            'class' => \app\models\User::class,
        ]
    ]
];
