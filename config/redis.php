<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'scheme'   => $_ENV['REDIS_SCHEME'],
    'host'     => $_ENV['REDIS_HOST'],
    'port'     => $_ENV['REDIS_PORT'],
    'password' => $_ENV['REDIS_PASSWORD'],
    'database' => $_ENV['REDIS_DATABASE']
];