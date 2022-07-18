<?php

use HNova\Rest\Funcs\FuncsURL;
use HNova\Rest\req;
use HNova\Rest\res;
use HNova\Rest\Response;
use HNova\Rest\root;

$url = "/" . req::getURL() . "/";
$url = str_replace('//', '/', $url);
$route = null;

function searh_url(string $url):array | Response | null {

    foreach ( ( $_ENV['api-rest-routes'] ?? [] )  as $key => $value ){

        if ( is_numeric( $key ) ){

            // Ejecutamos el middleware
            $result = $value();
            if ( !is_null($result)){
                if ($result instanceof Response ){
                    return $result;
                }
                return res::json( $result );
            }

        }else{
            // Establecemos la expreción regular
            $pattern = "/" . str_replace(':p', '(.+)', str_replace('/', '\/', "$key") ) . "/i";
            $pre = preg_match($pattern, $url);

            if ($pre != false || $key == '//'){


                if ($value['type'] == 'router' ){

                    $_ENV['api-rest-routes'] = [];
                    $value['load']();

                    $num_delete = substr_count( $value['url'] , '/');

                    $explode_url = explode('/', $url);

                    $url_new = "";
                    if ( is_int( $pre ) && $key != '//' ){

                        for ($i = $num_delete ; $i < count($explode_url) - 1 ; $i++ ){
                            $url_new .= "/" . trim( $explode_url[$i] ?? '' );
                        }
                        $url_new .= "/";
                    
                    }else{

                        $url_new = $url;
 
                    }

                    // Obtenemos los parametros
                    $_ENV['api-rest-req']['url-format'] =  $_ENV['api-rest-req']['url-format'] . ltrim( $value['url'], '/');

                    return searh_url($url_new);
                }else if ($value['type'] == 'route'){
                    // echo substr_count($url, '/') == substr_count($key, '/') ? "Si " : "No ";
                    // echo $key;
                    if (substr_count($url, '/') == substr_count($key, '/')){
                        return $value;
                    }

                }
            }
        }
       
    
    }
    return null;
}

$route = searh_url( $url, $_ENV['api-rest-routes'] ?? [] );

if ($route instanceof Response) return $route;

if ($route){

    if (array_key_exists(req::getMethod(), $route['methods'])){


        // Load body content
        require __DIR__ . '/load-content-body.php';

        $method = $route['methods'][req::getMethod()];

        // Extraemos los parametros de la ruta
        $url_format = $_ENV['api-rest-req']['url-format'] . ltrim($method['url'], '/');
        
        if (str_contains($url_format, ':')){
            $url_explode = explode('/', req::getURL());
            $url_format = explode("/", $url_format);

            for ($i = 1; $i < count($url_format); $i++){
                if (str_starts_with( $url_format[$i] , ':' )){
                    
                    $name = ltrim($url_format[$i], ':');
                    if ( array_key_exists( $name, $_ENV['api-rest-req']['params'] ) ){
                        throw new Exception("Nombre de parametros de URL repetido [$name]");
                    }else {
                        $_ENV['api-rest-req']['params'][$name] = $url_explode[$i];
                    }
                }
            }

        }

        foreach ($method['handlings'] as $handling){

            if ( is_callable($handling)){
                $result = $handling();
            }else{
                // Is un array
                $namespace = $handling[0] ?? null;
                $function = $function[1] ?? strtolower(req::getMethod());
                if ($namespace){

                    try {
                        $class = new $namespace();
                    } catch (\Throwable $th) {
                        throw new Exception("Error al inicializar la clase controloador\n" + $th->getMessage(), $th->getCode(), $th->getPrevious());
                    }

                    if (method_exists($class, $function)){
                        $result = $class->$function();
                    }else{
                        throw new Exception("La clase controlador no contiene la funcion solicitada [$namespace::$function]");
                    }

                }else{
                    throw new Exception("Error con el handlind de la ruta el namespace del controlador vacio []");
                }
            }


            if ( !is_null( $result ) ) {

                if ( $result instanceof  Response ){
                    return $result;
                }else{
                    return new Response('json', $result);
                }

            }
        }
        return res::text("*** No hubo repuesta del recurso ***")->status(500);
    }else{
        return res::text("*** Method not allowed ***")->status(405);
    }

}else{
    return res::text('*** Not fount ***')->status(404);
}