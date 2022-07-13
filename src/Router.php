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

class Router
{
    private array $routes = [];

    public function __construct(private string $path = '', private array $middlware = [])
    {
        
    }

    private function routerChildren(string $path, array $middlware = []):Router{
        return new Router($this->path . "/$path", [...$this->middlware, ...$middlware]);
    }

    private function add(string $method, string $path, ...$handlings){

        $path = "/" . trim($path, "/") . "/";

        $path = str_replace( '//', '/', $this->path . $path );
        $patterns[] = "/(:\w+)/i";
        $replacements[] = ':p';
        $path_key = preg_replace($patterns, $replacements, $path);

        if (!array_key_exists($path_key, api::getRoutes())){
            $_ENV['api-rest-routes'][$path_key] = [
                'path' => $path,
                'methods' => [] 
            ];
        }

        $_ENV['api-rest-routes'][$path_key]['methods'][$method] = [
            'path' => $path,
            'handlings' => array_merge($this->middlware, $handlings)
            // 'handlings' => $handlings
        ];
    }

    public function get($path, array|callable ...$handlings){
        $this->add('GET', $path, ...$handlings);
    }

    public function post($path, array|callable ...$handlings){
        $this->add('POST', $path, ...$handlings);
    }

    public function put($path, array|callable  ...$handlings){
        $this->add('PUT', $path, ...$handlings);
    }

    public function delete($path, array|callable ...$handlings){
        $this->add('DELETE', $path, ...$handlings);
    }

    public function pacth($path, array|callable ...$handlings){
        $this->add('PACHT', $path, ...$handlings);
    }
}