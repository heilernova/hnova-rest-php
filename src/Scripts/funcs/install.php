<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * https://codigonaranja.com/como-cambiar-el-color-del-texto-en-aplicaciones-de-consola-de-php
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

files::add("www/.htaccess", templates::get_htaccess());
files::add("www/index.php", templates::indexPublic($dir));
files::add("$dir/index.api.php", templates::indexApi());
files::add("$dir/.gitignore", "/bin/");

files::salve();