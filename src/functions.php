<?php

// Load environment variables and set them in the global scope
function loadEnvVars($envPath)
{
    if (file_exists($envPath)) {
        $envVars = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($envVars as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            list($name, $value) = explode('=', $line, 2);
            $_ENV[$name] = $value; // Load into the environment
        }
    }
}

// Determining the protocol (HTTP or HTTPS) to ensure proper URL formation
// Returns base URL and current URL
function getUrlInfo()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $base_url = $protocol . $_SERVER['HTTP_HOST'] . "/";
    $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return [$base_url, $current_url];
}

// Determine and return the content file path based on the request
function getContentFilePath()
{
    list($requestPath) = explode('?', trim($_SERVER['REQUEST_URI'], '/'), 2);
    $requestPath = empty($requestPath) ? 'home' : $requestPath;
    $contentFilePath = 'content/';
    $pathParts = explode('/', $requestPath);
    foreach ($pathParts as $index => $part) {
        $contentFilePath .= $part;
        $contentFilePath .= ($index < count($pathParts) - 1) ? '/' : (is_dir($contentFilePath) ? '/index.php' : '.php');
    }
    return $contentFilePath;
}
