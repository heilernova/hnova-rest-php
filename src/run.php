<?php

use HNova\Rest\HttpFuns;
use HNova\Rest\req;
use HNova\Rest\res;
use HNova\Rest\Response;

// Cargamos los ruta del directorio

$dir = "";
foreach (get_required_files() as $path){
    if (str_ends_with($path, 'index.api.php')){
        $_ENV['api-rest-dir'] = dirname($path);
        break;
    }
}

// Load app.json

$path_app_json = $_ENV['api-rest-dir'] . "/app.json";

if (file_exists($path_app_json)){
    $app_json = json_decode(file_get_contents($path_app_json), true);
    $_ENV['api-rest-config'] = $app_json; 
}else{
    return res::text("Error al cargar app.json")->status(500);
}

$url = $_SERVER['REQUEST_URI'];
$path_script = dirname($_SERVER['SCRIPT_NAME']);
$uri = substr($url, strlen($path_script));

/*****************************************************************************
 * Cargamos los datos de la req
 */
$_ENV['api-rest-req']['method'] = $_SERVER['REQUEST_METHOD'];
$_ENV['api-rest-req']['url'] = trim($uri, "/");
$_ENV['api-rest-req']['ip'] = HttpFuns::getIp();
$_ENV['api-rest-req']['device'] = HttpFuns::getDevice();
$_ENV['api-rest-req']['platform'] = HttpFuns::getPlatform();

if (!isset($_ENV['api-rest-routes'])){
    $_ENV['api-rest-routes'] = [];
}

$url = req::getURL();

// Validamos los use iniciales
$use = $_ENV['api-rest-routes'];
$_ENV['api-rest-routes'] = [];

$routes_config = $_ENV['api-rest-config']['routesConfig'];

$route_cofig = $routes_config['/'] ?? null;

foreach ($use as $key => $value){
    if (str_starts_with("/$url/", "$key/")){

        if (is_string($value)){
            $url = substr("/$url/", strlen("$key/"));


            if (array_key_exists($key, $routes_config)){
                $route_cofig = $routes_config[$key];
            }

            require $value;
        }
        break;
    }
}
// echo json_encode($routes_config); exit;
$_ENV['api-rest-cors'] = $route_cofig['cors'];
require __DIR__ . '/Funcs/load-cors.php';

/**************************************************************************************************
 * Cargamos Rutas
 */
$route = null;
$url = "/" . trim($url, '/') . "/";

foreach ($_ENV['api-rest-routes'] as $key => $value){
    $pattern = "/" . str_replace(':p', '(.+)', str_replace('/', '\/', "$key") ) . "/i";
    if (preg_match($pattern, $url) != false && substr_count($url, '/') == substr_count($key, '/')){

        // Caramos los parametros
        $explode_path = explode('/', $value['path']);
        $explode_url  = explode('/', $url);
        $params_url = [];
        for ($i = 1; $i < count($explode_path); $i++){
            $item = $explode_path[$i];
            if (str_starts_with($item, ':')){
                $params_url[ltrim($item, ':')] = $explode_url[$i] ;
            }
        }

        $_ENV['api-rest-req']['params'] = $params_url;
        $route = $value;
        break;
    }
}

if ($route){
    // Mapeamos los datos.
    $_ENV['api-rest-req']['headers'] = apache_request_headers();
    $content_type = $_ENV['api-rest-req']['headers']['Content-Type'] ?? null;

    if ($content_type){
        $type = explode(';', $content_type)[0];
        switch ($type){
            case "application/json":
                $_ENV['api-rest-req']['body'] = json_decode(file_get_contents('php://input'));
                break;

            case "multipart/form-data":
                switch (req::getMethod()) {
                    case 'GET':
                        $_ENV['api-rest-req']['body'] = $_GET;
                        $_ENV['api-rest-req']['files'] = $_FILES;
                        break;

                    case 'POST':
                        $_ENV['api-rest-req']['body'] = $_POST;
                        $_ENV['api-rest-req']['files'] = $_FILES;
                        break;

                    default:
                        $_ENV['api-rest-req']['body'] = require __DIR__ . '/Funcs/body-parce-form-data.php';
                        $_ENV['api-rest-req']['files'] = $_FILES;
                        break;
                }
                if (req::getMethod() != 'POST')

                break;
            default:
                break;
        }
    }

    if (array_key_exists(req::getMethod(), $route['methods'])){
        foreach ($route['methods'][req::getMethod()]['handlings'] as $hadling){
            $res = $hadling();
            if ($res != null){
                if ($res instanceof Response){
                    return $res;
                }else{
                    return new Response('json', $res);
                }
                break;
            }
        }
    }else{
        return new Response('text', "method not allowed", 405);
    }
}else{
    return new Response('text', null, 404);
}
