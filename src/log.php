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

class log
{
    public static function error($error){

    }

    public static function request(){
        $log = [
            'url' => req::getURL(),
            'method' => req::getMethod(),
            'status' => 200,
            'ip' => req::ip(),
            'device' => req::device(),
            'platform' => req::platform()
        ];
    }
}