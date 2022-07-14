<?php

// Mapeamos los datos.

use HNova\Rest\Http\FormDataFile;
use HNova\Rest\req;

$_ENV['api-rest-req']['headers'] = apache_request_headers();
$content_type = $_ENV['api-rest-req']['headers']['Content-Type'] ?? null;

if ( $content_type ){
    $type = explode(';', $content_type)[0];

    switch ( $type ){

        // JSON
        case 'application/json':
            $_ENV['api-rest-req']['body'] = json_decode(file_get_contents('php://input'));
            break;

        // Form data
        case 'multipart/form-data':
            switch (req::getMethod()) {
                case 'GET':
                    $_ENV['api-rest-req']['body'] = $_GET;
                    break;

                case 'POST':
                    $_ENV['api-rest-req']['body'] = $_POST;
                    break;
                
                default:
                    $_ENV['api-rest-req']['body'] = require __DIR__ . './../Funcs/body-parce-form-data.php';
                    break;
            }
        
        default:
            break;
    }
}

// Load files
$_ENV['api-rest-req']['files'] =  array_map(function($file){
    return new FormDataFile(
        $file['name'],
        $file['type'],
        $file['full_name'] ?? '',
        $file['tmp_name'],
        $file['error'],
        $file['size']
    );
}, $_FILES);