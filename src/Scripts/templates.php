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

class templates
{
    public static function get_htaccess():string{
        return "RewriteEngine On\nRewriteRule ^(.*) index.php?rest-router=$1 [L,QSA]";
    }

    public static function indexPublic(string $dir):string {
        return "<?php\nrequire '../$dir/index.api.php';";
    }

    public static function indexApi():string {
        $content = "<?php\n\n";
        $content .= "require __DIR__ . '/../vendor/autoload.php';\n\n";
        $content .= "use HNova\Rest\api;\n\n";
        $content .= "api::use('/', __DIR__ . '/Routes/index.routes.php');\n";
        $content .= 'api::run();';

        return $content;
    }

    public static function app():string {
        $text = "<?php\n\n";
        $text .= "namespace App;\n\n";
        $text .= "class app\n{\n    \n}";
        return $text;
    }

    public static function routes():string {
        return file_get_contents(__DIR__ . "/templates/routes.templates.php");
        // return $text;
    }
}