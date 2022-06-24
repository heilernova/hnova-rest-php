<?php

use HNova\Rest\api;

try{

    $cors = $_ENV['api-rest-cors'];
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
            http_response_code(500);
            echo "Error cors";
            exit(0);
        }
    }
} catch(\Throwable $th){
    throw $th;
}