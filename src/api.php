<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HNova\Rest;

use Exception;
use HNova\Rest\Funcs\FuncsURL;

class api
{
    public static function run():never{
        $res = null;
        try {

            $res = require __DIR__ . "/run.php";

            if ($res instanceof Response){
                $res->send();
            }
    
        } catch (\Throwable $th) {

            $error = "************** ERROR INESPERADO **************\n\n";
            $error .= "Message: " . $th->getMessage() . "\n\n";
            $error .= "Line: " . $th->getLine() . "\n";
            $error .= "File: " . $th->getFile() . "\n";

            $res = new Response('text', $error, 500);
            $res->send();
        }
        exit;
    }

    public static function getConfig():ApiConfig{
        return new ApiConfig();
    }

    public static function setDirFiles(string $dir):void {
        $_ENV['api-rest-dir-files'] = $dir;
    }

    public static function getDirFile(string $name):string {
        return ($_ENV['api-rest-dir-files'] ?? $_ENV['api-rest-dir']) . "/$name";
    }

    public static function use(...$arg){
        if ( count( $arg ) == 2 ){
            if (is_string($arg[0])){
                $url = str_replace('//', '/', $arg[0] . "/");

                $url_key = FuncsURL::getFormat($url);

                $_ENV['api-rest-routes'][$url_key] = [
                    'type' => 'router',
                    'url' => $url,
                    'load' => $arg[1]
                ];
            }else{
                throw new Exception("Error Processing Request", 1);
            }
        }else if ( count( $arg ) == 1 ){
            if ( is_callable( $arg[0] ) ){
                $_ENV['api-rest-middleware'][] = $arg[0];
            }
        }
    }

    public static function getRoutes():array{
        return $_ENV['api-rest-routes'];
    }

    public function getDir(string $path = null):string {
        return $_ENV['api-rest-dir'] . ($path ? "/$path" : '');
    }
}