<?php
namespace HNova\Rest;

use Exception;
use HNova\Rest\Config\DatabaseConfig;

class ApiConfig
{
    public DatabaseConfig $databases;

    public function __construct()
    {
        $this->databases = new DatabaseConfig();
    }

    public function getName():string{
        return $_ENV['api-rest-config']['name'] ?? '';
    }
}