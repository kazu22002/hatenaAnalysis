<?php

require_once __DIR__."/../vendor/autoload.php";

use App\Controllers\ReadPostController;

// .env
$dotenv = new Dotenv\Dotenv(__DIR__."/../config/");
$dotenv->load();

$controller = new ReadPostController();
$controller->read();
