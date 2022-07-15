<?php

use HNova\Rest\HttpFuns;

$_ENV['api-rest-req']['params'] = [];
$_ENV['api-rest-req']['method'] = $_SERVER['REQUEST_METHOD'];
$_ENV['api-rest-req']['url-format'] = '';
$_ENV['api-rest-req']['ip'] = HttpFuns::getIp();
$_ENV['api-rest-req']['device'] = HttpFuns::getDevice();
$_ENV['api-rest-req']['platform'] = HttpFuns::getPlatform();