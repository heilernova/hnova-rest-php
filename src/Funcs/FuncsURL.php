<?php
namespace HNova\Rest\Funcs;

use Exception;

class FuncsURL
{
    public static function getFormat(string $url):string{
        $url = str_replace('//', '/', "/$url/");
        // echo $url;
        $patterns[] = "/(:\w+)/i";
        $replacements[] = ':p';
        return preg_replace($patterns, $replacements, $url);
    }
}