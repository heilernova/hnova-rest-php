<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use HNova\Rest\Http\FormDataFile;
use HNova\Rest\HttpFuns;
use HNova\Rest\req;
use HNova\Rest\res;
use HNova\Rest\Response;
use HNova\Rest\root;

$_ENV['api-rest-route'] = [];

$url = $_SERVER['REQUEST_URI'];
$path_script = dirname($_SERVER['SCRIPT_NAME']);
$uri = substr($url, strlen($path_script));
$_ENV['api-rest-req']['url'] = trim($uri, "/");

require __DIR__ . '/Run/load-directories.php';
require __DIR__ . '/Run/load-cors.php';
require __DIR__ . '/Run/load-req-info.php';

return require __DIR__ . '/Run/search-route.php';