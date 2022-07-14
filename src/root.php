<?php
namespace HNova\Rest;

class root{
    public static function echo_json($value):never{
        header("Content-Type: application/json");
        echo json_encode($value);
        exit;
    }

    /**
     * @return object|callable
     */
    public static function getRoutes():array{
        return $_ENV['api-rest-routes'];
    }
}