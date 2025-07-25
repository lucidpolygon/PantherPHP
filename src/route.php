<?php

declare(strict_types=1);

/**
 * Get base URL and current URL with query parameters preserved.
 *
 * @return array{string, string} [base_url, current_url]
 */
function getUrlInfo(): array
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/';
    $currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return [$baseUrl, $currentUrl];
}

/**
 * Parse the request URI into path and query parameters.
 *
 * @return array{string, array} [path, query_params]
 */
function parseRequest(): array
{
    $requestUri = trim($_SERVER['REQUEST_URI'], '/');
    $parts = parse_url($requestUri);
    $path = $parts['path'] ?? '';
    parse_str($parts['query'] ?? '', $queryParams);
    return [$path, $queryParams];
}

/**
 * Handle routing and return the content file path.
 *
 * @return string Path to the content file
 */
function handleRoutes(): string
{
    [$requestPath, $queryParams] = parseRequest();
    $baseContentPath = 'content/';

    // Make query parameters globally accessible if needed (optional, adjust as per use case)
    define('QUERY_PARAMS', $queryParams);

    // Load redirects from JSON
    $redirectFile = BASE_PATH . '/data/redirects.json';
    if (file_exists($redirectFile)) {
        $redirects = json_decode(file_get_contents($redirectFile), true);
        if (is_array($redirects) && isset($redirects[$requestPath])) {
            header('Location: ' . $redirects[$requestPath], true, 302);
            exit;
        }
    }

    // Special routes
    if ($requestPath === 'generate-sitemap') {
        generateSitemap();
        $redirectTo = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $redirectTo);
        exit;
    }

    // Default to 'home' if no path
    $requestPath = empty($requestPath) ? 'home' : $requestPath;
    $pathParts = explode('/', $requestPath);
    $contentFilePath = $baseContentPath;

    // Build the file path incrementally
    foreach ($pathParts as $index => $part) {
        $contentFilePath .= $part;
        $isLastPart = $index === count($pathParts) - 1;

        if (!$isLastPart && is_dir(BASE_PATH . '/' . $contentFilePath)) {
            $contentFilePath .= '/';
            continue;
        }

        $fullPath = BASE_PATH . '/' . $contentFilePath . '.php';
        if (file_exists($fullPath)) {
            return $contentFilePath . '.php';
        }

        // Check for index.php in a directory
        $indexPath = BASE_PATH . '/' . $contentFilePath . '/index.php';
        if (is_dir(BASE_PATH . '/' . $contentFilePath) && file_exists($indexPath)) {
            return $contentFilePath . '/index.php';
        }

        // If no match and it's the last part, stop and fallback
        if ($isLastPart) {
            break;
        }
    }

    return $baseContentPath . '404.php';
}