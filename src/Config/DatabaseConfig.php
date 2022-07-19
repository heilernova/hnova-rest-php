<?php
namespace HNova\Rest\Config;

use Exception;
use PDO;

class DatabaseConfig
{
    public function getPDO(string $name):PDO{
        $config = $_ENV['api-rest-config']['databases'][$name] ?? null;

        if (is_null($config)) throw new Exception("No hay configuración de la base de datos [ $name ] en el app.json");

        try {
            $dns = $config['type'] . ":host=" . $config['hostname'] . "; dbname=" . $config['database']; 
            return new PDO($dns, $config['username'], $config['password']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getList():array {
        return $_ENV['api-rest-config']['databases'];
    }
}