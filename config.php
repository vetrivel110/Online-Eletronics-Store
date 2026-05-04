<?php

/**
 * Loads key/value pairs from .env into process environment variables.
 */
if (!defined('APP_CONFIG_BOOTSTRAPPED')) {
    $envFile = __DIR__ . DIRECTORY_SEPARATOR . '.env';

    if (is_readable($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines !== false) {
            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '' || $line[0] === '#') {
                    continue;
                }

                $pair = explode('=', $line, 2);
                if (count($pair) !== 2) {
                    continue;
                }

                $key = trim($pair[0]);
                $value = trim($pair[1]);

                if ($key === '') {
                    continue;
                }

                // Strip optional surrounding quotes.
                $value = preg_replace('/^([\"\'])(.*)\\1$/', '$2', $value);

                if (getenv($key) === false) {
                    putenv($key . '=' . $value);
                }

                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    define('APP_CONFIG_BOOTSTRAPPED', true);
}

if (!function_exists('env_value')) {
    function env_value(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('db_connect')) {
    function db_connect(): mysqli
    {
        $host = env_value('DB_HOST', 'localhost');
        $user = env_value('DB_USER', 'root');
        $pass = env_value('DB_PASS', '');
        $name = env_value('DB_NAME', 'tech_world_db');
        $port = (int) env_value('DB_PORT', '3306');

        $conn = new mysqli($host, $user, $pass, $name, $port);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        return $conn;
    }
}
