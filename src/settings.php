<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        // PDO setting for mySQL
        'db' => [
            'host' => "localhost",
            'user' => "root",
            'pass' => "T00R123",
            'dbname' => "2cloud"
        ],
    ],
];
