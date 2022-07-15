<?php

use HNova\Rest\api;
use HNova\Rest\root;

try{
    $routes_config = $_ENV['api-rest-config']['routesConfig'];
    $cors = null;
    $url = '/' . $_ENV['api-rest-req']['url'] ?? '';
    // echo $url;
    foreach ($routes_config as $key => $config){
        if (str_starts_with($url, $key)){
            $cors = $config['cors'];
        }
    }
    
    if ($cors){
        $fun = function(array|string|null $data):?string{
            if ($data){
                if (is_array($data)){
                    $text = "";
                    foreach ($data as $value){
                        $text .= ", $value";
                    }
                    return ltrim($text, ", ");
                }else{
                    return $data;
                }
            }else{
                return null;
            }
        };

        $origin  = $fun($cors['origin']);
        $headers = $fun($cors['allowedHeaders']);
        $methods = $fun($cors['methods']);
    
        if ($origin) header("Access-Control-Allow-Origin:  $origin");
        if ($headers) header("Access-Control-Allow-Headers: $headers");
        if ($methods) header("Access-Control-Allow-Methods: $methods");
    
        if (isset($_SERVER['HTTP_Origin'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_Origin']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
                if ($headers) header("Access-Control-Allow-Methods: $headers");
            }
    
            if ($headers){
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
                    header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
            }

            exit(0);
        }
    }
} catch(\Throwable $th){
    throw new Exception("Error al establecer los CORS\n\n" . $th->getMessage(), $th->getCode(), $th->getPrevious());
}