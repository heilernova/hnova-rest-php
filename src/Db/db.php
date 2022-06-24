<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * https://codigonaranja.com/como-cambiar-el-color-del-texto-en-aplicaciones-de-consola-de-php
 */
namespace HNova\Rest\Db;

use Exception;
use PDO;
use PDOStatement;

class db
{
    private static PDOStatement|null $stmt = null;
    private static string $last_query = "";

    private static function getPDO():PDO{
        $config = $_ENV['api-rest-db']['pdo'] ?? null; 
        if (!$config){
            throw new Exception("No se econtro la configuracion por defecto de la conexion PDO");
        }
        return $config;
    }

    private static function getChartFormat():string {
        return $_ENV['api-rest-db']['char'] ?? '';
    }

    private static function execute(string $sql, array $params = null):DbResult {
        try {
            if (!self::$stmt){
                self::$stmt = self::getPDO()->prepare($sql);
                self::$last_query = $sql;
            }else{
                if ($sql != self::$last_query){
                    self::$stmt = self::getPDO()->prepare($sql);
                }
            }

            if (self::$stmt->execute($params)){

                $res = new DbResult();

                $res->rows = self::$stmt->fetchAll(PDO::FETCH_ASSOC);
                $res->rowCount = count($res->rows);
                return $res;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function query(string $sql, array $params = null):DbResult{
        return self::execute($sql, $params);
    }

    /********************************************************************************
     * 
     */
    public static function insert(array $params, string $table){
        $fields = "";
        $values = "";
        foreach ($params as $key => $value){
            $fields .= ", " . self::getChartFormat() . $key . self::getChartFormat();
            $values .= ", :$key";
        }
        $fields = ltrim($fields, ', ');
        $values = ltrim($values, ', ');

        $table = self::getChartFormat() . $table . self::getChartFormat();

        return self::execute("INSERT INTO $table($fields) VALUES($values)", $params);
    }

    /**
     * 
     */
    public static function update(array $params, string|array $condition, string $table){
        $values = "";
        foreach ($params as $key => $value){
            $values .= ", " . self::getChartFormat() . $key . self::getChartFormat() . "=:$key";
        }
        $values = ltrim($values, ', ');

        if (is_string($condition)) $condition = [$condition, []];

        $where = str_replace(':', ':pw_', $condition[0]);


        $where = preg_replace_callback(['/\w+=/i'], function($text){
            $tex = trim(explode('=', $text[0])[0]);
            return self::getChartFormat() . $tex . self::getChartFormat() . "=";
        }, str_replace(['= ', ' =', ' = '], '=', $where));

        foreach ($condition[1] as $key => $value) {
            $params["pw_$key"] = $value;
        }

        $table = self::getChartFormat() . $table . self::getChartFormat();

        return self::execute("UPDATE $table SET $values WHERE $where", $params);
    }

    /*********************************************************************************
     * 
     */
    public static function delete(string $condition, array $params = null, string $table = null){
        $table = self::getChartFormat() . $table . self::getChartFormat();
        $where = $condition;
        $where = preg_replace_callback('/\w+=/i', function($item){
            return self::getChartFormat() . trim(explode('=',$item[0])[0]) . self::getChartFormat() . "=";
        }, $where);
        return self::execute("DELETE FROM $table WHERE $where", $params);
    }

}