<?php

require __DIR__ . '/../bootstrap.php';

$router = new Router();
require __DIR__ . '/../routes/admin.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uri = preg_replace('#^/admin#', '', $uri) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri);

