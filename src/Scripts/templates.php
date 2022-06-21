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
        return "<?php\n\nrequire __DIR__ . '/../vendor/autoload.php';";
    }
}