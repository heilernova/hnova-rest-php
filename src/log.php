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
            'status' => $_ENV['api-rest-res']['status'] ?? 20,
            'ip' => req::ip(),
            'device' => req::device(),
            'platform' => req::platform()
        ];

        $dir = $_ENV['api-rest-dir'];
        $dir_log = "$dir/Logs/requests.log";
        if ( !file_exists("$dir/Logs") ) mkdir("$dir/Logs");
        $line = "[" . date('Y-m-d H:i:s P', time()). "]";
        $line .= "  JSON: " . json_encode($log);
        
        $file = fopen($dir_log, 'a');
        fputs($file, "$line\n");
    }
}