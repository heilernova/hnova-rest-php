<?php

use HNova\Rest\Router;

$routes = new Router();

$routes->get('/', function(){ 
    return "Hola mundo"; 
});