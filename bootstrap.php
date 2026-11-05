<?php

session_start();

date_default_timezone_set('Asia/Yekaterinburg');

require __DIR__ . '/config/config.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/core/helpers.php';

spl_autoload_register(static function (string $class): void {
    $paths = [
        ROOT_PATH . '/core/' . $class . '.php',
        ROOT_PATH . '/controllers/' . $class . '.php',
        ROOT_PATH . '/controllers/admin/' . $class . '.php',
        ROOT_PATH . '/models/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

