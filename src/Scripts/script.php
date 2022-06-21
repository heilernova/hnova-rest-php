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
use Composer\Script\Event;

class script
{
    private static $_dir_name_src = "src";
    private static array $_arguments = [];

    public static function test(Event $event){
        self::$_dir_name_src = "api";

        self::execute($event);
    }

    public static function getArgument():?string{
        return array_shift(self::$_arguments);
    }

    public static function getDir(string $dir = null):string{
        return self::$_dir_name_src . ($dir ? "/$dir" : '');
    }

    public static function execute(Event $event){
        try {
            self::$_arguments = $event->getArguments();
    
            $arg = self::getArgument();

            if ($arg){                
                if ($arg == 'i' || $arg == 'install'){
                    require __DIR__ . "/funcs/install.php";
                }else{
        
                }
            }else{
                console::error("*** ERROR - undefined command ***");
            }
    
        } catch (\Throwable $th) {
            //throw $th;
            console::error("*** ERROR ***");
            console::log(" Message: " . $th->getMessage());
            console::log(" File:    " . $th->getFile());
            console::log(" Line:    " . $th->getLine());
        }
    }

}