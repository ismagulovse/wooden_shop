<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $hosts = array_values(array_unique([DB_HOST, 'localhost', '127.0.0.1']));
    $lastError = null;

    foreach ($hosts as $host) {
        try {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, DB_PORT, DB_NAME);
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            $lastError = $e;
        }
    }

    http_response_code(500);
    echo '<h1>Ошибка подключения к БД</h1>';
    echo '<p>Проверьте, что MySQL запущен и база <strong>' . htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8') . '</strong> импортирована из database.sql.</p>';
    if ($lastError instanceof PDOException) {
        echo '<p><small>' . htmlspecialchars($lastError->getMessage(), ENT_QUOTES, 'UTF-8') . '</small></p>';
    }
    exit;
}