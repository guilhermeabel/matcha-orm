<?php

function config($key)
{
    static $config = null;

    if ($config === null) {
        $configFile = __DIR__ . '\config\database.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        } else {
            throw new Exception('Configuration file not found: ' . $configFile);
        }
    }

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $key) {
        if (isset($value[$key])) {
            $value = $value[$key];
        } else {
            throw new Exception('Configuration key not found: ' . $key);
        }
    }

    return $value;
}