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

class req
{
    public static function getURL():string{
        return $_ENV['api-rest-req']['url'];
    }

    public static function getMethod():string{
        return $_ENV['api-rest-req']['method'];
    }

    public static function body():mixed {
        return $_ENV['api-rest-req']['body'] ?? null;
    }

    public static function files():array {
        return $_ENV['api-rest-req']['files'] ?? [];
    }

    public static function params($assoc = true):array {
        return $_ENV['api-rest-req']['params'];
    }

    public static function device():int{
        return $_ENV['api-rest-req']['device'];
    }

    public static function ip():string {
        return $_ENV['api-rest-req']['ip'];
    }

    public static function platform():string {
        return $_ENV['api-rest-req']['platform'];
    }
}