<?php
// Cargamos los ruta del directorio
$dir = "";
foreach (get_required_files() as $path){
    if (str_ends_with($path, 'index.api.php')){
        $_ENV['api-rest-dir'] = dirname($path);
        break;
    }
}
