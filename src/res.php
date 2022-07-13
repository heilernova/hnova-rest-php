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

use HNova\Rest\Response;

class res
{
    public static function sendStatus(int $status):Response {
        return new Response('text', status: $status);
    }
    
    public static function json($value):Response {
        return new Response('json', $value);
    }

    public static function file(string $value):Response {
        return new Response('file', $value);
    }

    public static function html(string $value):Response {
        return new Response('html', $value);
    }

    public static function text(string $text):Response {
        return new Response('text', $text);
    }
}