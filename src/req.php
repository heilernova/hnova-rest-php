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

use HNova\Rest\Http\FormDataFile;

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

    /**
     * @return FormDataFile[]
     */
    public static function files():array {
        return $_ENV['api-rest-req']['files'] ?? [];
    }

    /**
     * @param string[]|object
     */
    public static function params(bool $assoc = false):array|object {
        return $assoc ? $_ENV['api-rest-req']['params'] : (object)$_ENV['api-rest-req']['params'];
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