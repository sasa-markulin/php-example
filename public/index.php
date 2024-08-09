<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Router;

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch();
