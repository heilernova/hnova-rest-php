<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace HNova\Rest\Scripts;

console::log("Install packages");

// We validate that the install has not been execute.
if (file_exists(script::getDir('app.json'))){
    console::error("*** Error ***");
    console::log("  Ya se ejecuto el instalador");
    exit;
}

// We verify that the folder is free for your installation
$dir = script::getDir();

if (!file_exists($dir)){
    mkdir($dir);
}else{
    if (filesize($dir)){
        console::error("*** ERROR ***");
        console::log("El directorio [ $dir ] ya esta en uso");
        exit;
    }
}

// Data app.json
$json_data = [
    'name'  => 'My app',
    'debug' => true,
    'developers' => [],
    'databases' => (object)[
        'test' => [
            'type' => 'mysql',
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'test'
        ]
    ],
    'routesConfig' => (object)[
        '/' => [
            'cors' => [
                'origin' => '*',
                'methods' => '*',
                'allowedHeaders' => '*'
            ]
        ]
    ]
];

files::add("www/.htaccess", templates::get_htaccess());
files::add("www/index.php", templates::indexPublic($dir));
files::add("$dir/app.php", templates::app());
files::add("$dir/index.api.php", templates::indexApi());
files::add("$dir/app.json", str_replace('\/','/', json_encode($json_data, 128)));
files::add("$dir/.gitignore", "/Logs/");
files::add("$dir/Routes/index.routes.php", templates::routes());

files::salve();