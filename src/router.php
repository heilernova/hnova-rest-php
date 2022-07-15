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

class router
{
    private static function addRoute(string $method, string $path, ...$handlings){
        $path = "/" . trim($path, "/") . "/";
        $path = str_replace( '//', '/', $path );

        $patterns[] = "/(:\w+)/i";
        $replacements[] = ':p';
        $path_key = preg_replace($patterns, $replacements, $path);

        if ( !array_key_exists( $path_key, $_ENV['api-rest-routes'] ?? [] ) ){

            $_ENV['api-rest-routes'][$path_key] = [
                'type' => 'route',
                'path' => $path,
                'methods' => []
            ];

        }
        $_ENV['api-rest-routes'][$path_key]['methods'][$method] = [
            'url' => $path,
            'handlings' => $handlings
        ];

    }

    public static function use(...$arg){
        $num_arg = count($arg);

        if ( count($arg) == 2 ){
            if (is_string( $arg[0] ) && is_callable($arg[1]) ){
                $url_key = FuncsURL::getFormat( $arg[0] );
                $path = str_replace('//', '/', "/$arg[0]/");
                $_ENV['api-rest-routes'][$url_key] = [
                    'type' => 'router',
                    'url' => $path,
                    'load' => $arg[1]
                ];
            }else{
                throw new Exception("Error con el argumente use");
            }
        }else if ( $num_arg == 1 ){
            if ( is_callable( $arg[0] ) ){
                $_ENV['api-rest-routes'][] = $arg[0];
            }
        }
    }

    public static function get(string $path, array|callable ...$hadling): void{
        self::addRoute('GET', $path, ...$hadling);
    }

    public static function post(string $path, array|callable ...$hadling): void {
        self::addRoute('POST', $path, ...$hadling);
    }

    public static function put(string $path, array|callable ...$hadling): void {
        self::addRoute('PUT', $path, ...$hadling);
    }

    public static function delete(string $path, array|callable ...$handlings){
        self::addRoute('DELETE', $path, ...$handlings);
    }

    public static function pacth(string $path, array|callable ...$handlings){
        self::addRoute('PACHT', $path, ...$handlings);
    }
}