<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HNova\Rest\Scripts;

class files 
{
    private static array $fileList = [];

    static function add($path, $content):void {
        self::$fileList[] = [
            'path' => $path,
            'content' => $content
        ];
    }

    static function salve():void {
        foreach (self::$fileList as $file){

            $dir_name = dirname($file['path']);
            if (!file_exists($dir_name)){
                $dir_names = explode('/', $dir_name);
                $dir = ".";
                foreach ($dir_names as $name){
                    $dir .= "/$name";
                    if (!file_exists($dir)) mkdir($dir);
                }
            }
            $edit =  file_exists($file['path']);
            $open = fopen($file['path'], $edit ? 'w' : 'a');
            fputs($open, $file['content']);
            fclose($open);

            if ($edit){
                console::fileUpdate($file['path']);
            }else{
                console::fileCreate($file['path']);
            }
        }
    }
}