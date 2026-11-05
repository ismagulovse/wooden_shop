<?php

require __DIR__ . '/bootstrap.php';

$router = new Router();
require __DIR__ . '/routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri);

