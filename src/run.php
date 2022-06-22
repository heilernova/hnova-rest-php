<?php

use HNova\Rest\req;
use HNova\Rest\Response;

$url = req::getURL();

// Validamos los use iniciales
$use = $_ENV['api-rest-routes'];
$_ENV['api-rest-routes'] = [];

foreach ($use as $key => $value){
    if (str_starts_with("/$url/", "$key/")){

        if (is_string($value)){
            $url = substr("/$url/", strlen("$key/"));
            require $value;
        }
        break;
    }
}

// Cargamos las truas
$route = null;
$url = "/" . trim($url, '/') . "/";

foreach ($_ENV['api-rest-routes'] as $key => $value){
    $pattern = "/" . str_replace(':p', '(.+)', str_replace('/', '\/', "$key") ) . "/i";
    if (preg_match($pattern, $url) != false){
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
