<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // 'db' => [
        //     'host'   => "35.198.59.23",
        //     'user'   => "root",
        //     'pass'   => "u9C@sh",
        //     'dbname' => "upcash"
        // ],
        'db' => [
            'host'   => "den1.mysql4.gear.host",
            'user'   => "recebiveis",
            'pass'   => "Aj7pv~3C_e6H",
            'dbname' => "recebiveis"
        ],

        // Monolog settings
        'logger' => [
            'name' => 'upcash-log',
            'path' => '../logs/app.log',
            'level' => \Monolog\Logger::DEBUG
        ]
    ],
];

// Add custom configs
// $config['displayErrorDetails'] = true;

// $config['db']['host']   = "35.198.59.23";
// $config['db']['user']   = "root";
// $config['db']['pass']   = "u9C@sh";
// $config['db']['dbname'] = "upcashdb";