<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HNova\Rest\Db;

use PDO;

class db_connection
{
    private static string $error = "";

    public static function connect($data):bool{
        try {
            $type = $data['type'];
            $hostname = $data['hostname'];
            $username = $data['username'];
            $password = $data['password'];
            $database = $data['database'];

            $pdo = new PDO("$type:host=$hostname; dbname=$database", $username, $password);
            $_ENV['api-rest-db']['type'] = $type;
            $_ENV['api-rest-db']['pdo'] = $pdo;
            $_ENV['api-rest-db']['char'] = $type == 'mysql' ? '`' : ($type == 'pgsql' ? '"' : '');

            return true;
        } catch (\Throwable $th) {
            //throw $th;
            self::$error = $th->getMessage();
            return false;
        }

        
    }

    public static function getError():string {
        return self::$error;
    }
}