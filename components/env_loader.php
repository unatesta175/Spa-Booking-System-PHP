<?php
/**
 * Environment Variables Loader
 * Loads environment variables from .env file
 */

function loadEnv($filePath = __DIR__ . '/../.env') {
    if (!file_exists($filePath)) {
        die('Error: .env file not found. Please create one based on .env.example');
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse the line
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                $value = $matches[2];
            }

            // Set environment variable
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}

function env($key, $default = null) {
    return isset($_ENV[$key]) ? $_ENV[$key] : (getenv($key) ?: $default);
}

// Auto-load environment variables
loadEnv();



