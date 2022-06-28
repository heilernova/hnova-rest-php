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

class api
{
    public static function run():never{
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

    public static function getConfig():object{
        return (object)[];
    }

    public static function setDirFiles(string $dir):void {
        $_ENV['api-rest-dir-files'] = $dir;
    }

    public static function getDirFile(string $name):string {
        return ($_ENV['api-rest-dir-files'] ?? $_ENV['api-rest-dir']) . "/$name";
    }

    public static function use(string $path, string|Route $router){
        $_ENV['api-rest-routes'][$path] = $router;
    }

    public static function getRoutes():array{
        return $_ENV['api-rest-routes'];
    }

    public function getDir(string $path = null):string {
        return $_ENV['api-rest-dir'] . ($path ? "/$path" : '');
    }
}