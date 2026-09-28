<?php

declare(strict_types=1);

/**
 * Get base URL and current URL with query parameters preserved.
 * Supports reverse proxies/CDNs via X-Forwarded-* headers.
 *
 * @return array{string, string} [base_url, current_url]
 */
function getUrlInfo(): array
{
    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
    $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    $protocol = $proto
        ? $proto . '://'
        : (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) == 443)) ? 'https://' : 'http://');

    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $baseUrl = $protocol . $host . '/';
    $currentUrl = $protocol . $host . $requestUri;

    return [$baseUrl, $currentUrl];
}

/**
 * Parse the request URI into path and query parameters.
 *
 * @return array{string, array} [path, query_params]
 */
function parseRequest(): array
{
    $requestUri = trim($_SERVER['REQUEST_URI'] ?? '/', '/');
    $parts = parse_url($requestUri) ?: [];
    $path = trim((string) ($parts['path'] ?? ''), '/');
    parse_str((string) ($parts['query'] ?? ''), $queryParams);

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
    $previewToken = $_ENV['PREVIEW_TOKEN'] ?? '';
    $isPreviewRequest = isset($queryParams['preview'])
        && $previewToken !== ''
        && hash_equals($previewToken, (string) $queryParams['preview']);

    if (!defined('QUERY_PARAMS')) {
        define('QUERY_PARAMS', $queryParams);
    }

    // Load redirects from JSON. Supports both:
    // {"old-path": "/new-path"} and {"old-path": {"to": "/new-path", "status": 301}}
    $redirectFile = BASE_PATH . '/data/redirects.json';
    if (file_exists($redirectFile)) {
        $redirects = json_decode((string) file_get_contents($redirectFile), true);
        if (is_array($redirects) && array_key_exists($requestPath, $redirects)) {
            $redirect = $redirects[$requestPath];
            $redirectTo = is_array($redirect) ? ($redirect['to'] ?? null) : $redirect;
            $statusCode = is_array($redirect) ? (int) ($redirect['status'] ?? 302) : 302;

            if (is_string($redirectTo) && in_array($statusCode, [301, 302], true)) {
                header('Location: ' . $redirectTo, true, $statusCode);
                exit;
            }
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
    $requestPath = $requestPath === '' ? 'home' : $requestPath;

    // Keep route resolution inside /content.
    if (str_contains($requestPath, '..') || str_starts_with($requestPath, '.') || str_contains($requestPath, "\0")) {
        http_response_code(404);
        return $baseContentPath . '404.php';
    }

    $pathParts = array_values(array_filter(explode('/', $requestPath), static fn ($part) => $part !== ''));
    $contentFilePath = $baseContentPath;

    foreach ($pathParts as $index => $part) {
        $contentFilePath .= $part;
        $isLastPart = $index === count($pathParts) - 1;

        if (!$isLastPart && is_dir(BASE_PATH . '/' . $contentFilePath)) {
            $contentFilePath .= '/';
            continue;
        }

        // Do not expose internal folders unless a valid draft preview token is supplied.
        foreach (['partials', 'draft', 'drafts'] as $folder) {
            if (str_starts_with($contentFilePath, $baseContentPath . $folder . '/')) {
                if (in_array($folder, ['draft', 'drafts'], true) && $isPreviewRequest) {
                    continue;
                }
                http_response_code(404);
                return $baseContentPath . '404.php';
            }
        }

        $fullPath = BASE_PATH . '/' . $contentFilePath . '.php';
        if (file_exists($fullPath)) {
            return $contentFilePath . '.php';
        }

        $indexPath = BASE_PATH . '/' . $contentFilePath . '/index.php';
        if (is_dir(BASE_PATH . '/' . $contentFilePath) && file_exists($indexPath)) {
            return $contentFilePath . '/index.php';
        }

        if ($isLastPart) {
            break;
        }
    }

    http_response_code(404);
    return $baseContentPath . '404.php';
}
