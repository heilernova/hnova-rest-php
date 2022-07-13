<?php
namespace HNova\Rest;

class root{
    public static function echo_json($value):never{
        header("Content-Type: application/json");
        echo json_encode($value);
        exit;
    }
}