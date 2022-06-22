<?php

namespace HNova\Rest;

class Router
{
    private array $routes = [];
    public function __construct(private string $path = '', array $middlware = [])
    {
        
    }

    private function add(string $method, string $path, ...$handlings){

        $path = "/" . trim($path, "/") . "/";

        $path = $this->path . $path;
        // $path = trim()
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
            'handlings' => $handlings
        ];
    }

    public function get($path, array|callable ...$handlings){
        $this->add('GET', $path, ...$handlings);
    }

    public function post($path, array|callable ...$handlings){
        $this->add('POST', $path, ...$handlings);
    }

    public function put($path, array|callable  ...$handlings){

    }

    public function delete($path, array|callable ...$handlings){

    }

    public function pacth($path, array|callable ...$handlings){

    }
}