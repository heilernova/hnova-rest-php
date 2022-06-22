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
namespace HNova\Rest;

class api
{
    public static function run():void{
        $url = $_SERVER['REQUEST_URI'];
        $path_script = dirname($_SERVER['SCRIPT_NAME']);
        $uri = substr($url, strlen($path_script));
        
        $_ENV['api-rest-req']['method'] = $_SERVER['REQUEST_METHOD'];
        $_ENV['api-rest-req']['url'] = trim($uri, "/");
        if (!isset($_ENV['api-rest-routes'])){
            $_ENV['api-rest-routes'] = [];
        }

        $res = null;
        try {
            $res = require __DIR__ . "/run.php";
        } catch (\Throwable $th) {

            $error = "************** ERROR INESPERADO **************\n\n";
            $error .= "Message: " . $th->getMessage() . "\n\n";
            $error .= "Line: " . $th->getLine() . "\n";
            $error .= "File: " . $th->getFile() . "\n";

            $res = new Response('text', $error, 500);
        }

        if ($res instanceof Response){
            $res->send();
        }

        exit;
    }

    public static function use(string $path, string|Route $router){
        $_ENV['api-rest-routes'][$path] = $router;
    }

    public static function getRoutes():array{
        return $_ENV['api-rest-routes'];
    }
}