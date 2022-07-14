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
    // public function __construct(private string $path = '', private array $middlware = [])
    // {
        
    // }

    // public static function routerChildren(string $path, array $middlware = []):Router{
    //     return new Router($this->path . "/$path", [...$this->middlware, ...$middlware]);
    // }

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
                $_ENV['api-rest-middleware'][] = $arg[0];
            }
        }
    }

    public static function get(string $path, array|callable ...$hadling): void{
        self::addRoute('GET', $path, ...$hadling);
    }

    // public static function use($path, callable $hadling){
    //     $path = "/" . trim($path, "/") . "/";
    //     $path = str_replace( '//', '/', $this->path . $path );
    //     $_ENV['api-rest-routes'][$path] = $hadling;
    // }

    // private static function add(string $method, string $path, ...$handlings){

    //     $path = "/" . trim($path, "/") . "/";

    //     $path = str_replace( '//', '/', $this->path . $path );
    //     $patterns[] = "/(:\w+)/i";
    //     $replacements[] = ':p';
    //     $path_key = preg_replace($patterns, $replacements, $path);

    //     if (!array_key_exists($path_key, api::getRoutes())){
    //         $_ENV['api-rest-routes'][$path_key] = [
    //             'path' => $path,
    //             'methods' => [] 
    //         ];
    //     }

    //     $_ENV['api-rest-routes'][$path_key]['methods'][$method] = [
    //         'path' => $path,
    //         'handlings' => array_merge($this->middlware, $handlings)
    //         // 'handlings' => $handlings
    //     ];
    // }

    // public static function get($path, array|callable ...$handlings){
    //     $this->add('GET', $path, ...$handlings);
    // }

    // public static function post($path, array|callable ...$handlings){
    //     $this->add('POST', $path, ...$handlings);
    // }

    // public function put($path, array|callable  ...$handlings){
    //     $this->add('PUT', $path, ...$handlings);
    // }

    // public static function delete($path, array|callable ...$handlings){
    //     $this->add('DELETE', $path, ...$handlings);
    // }

    // public static function pacth($path, array|callable ...$handlings){
    //     $this->add('PACHT', $path, ...$handlings);
    // }
}