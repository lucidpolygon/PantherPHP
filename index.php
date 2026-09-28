<?php

declare(strict_types=1);

/*
header("Content-Security-Policy: default-src 'self'; img-src *; script-src 'self' https://www.googletagmanager.com;");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: no-referrer");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
*/

define('BASE_PATH', realpath(__DIR__));

$logDir = BASE_PATH . '/logs';
if (is_dir($logDir)) {
    ini_set('error_log', $logDir . '/errors.log');
}

require_once BASE_PATH . '/src/functions.php';
require_once BASE_PATH . '/src/route.php';

loadEnvVars(BASE_PATH . '/.env');

// Project configuration settings. These can be overridden per page before output.
$project_name = env('PROJECT_NAME', 'PantherPHP');
$meta_title = env('META_TITLE', $project_name);
$meta_description = env('META_DESCRIPTION', 'A minimal PHP boilerplate for fast, content-led websites.');
$og_image_url = env('OG_IMAGE_URL', 'assets/imgs/og-image.png');
$ga4_id = env('GA4_ID', '');
$website_url = rtrim(env('WEBSITE_URL', ''), '/');
$no_index = false;
$canonical_url = null;

// Setting the asset version based on the last modified time of the CSS file for cache busting.
$asset_version = file_exists(BASE_PATH . '/assets/css/output.css')
    ? filemtime(BASE_PATH . '/assets/css/output.css')
    : time();

// Handle simple form submissions if you keep the built-in forms.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    handleFormSubmission();
}

[$base_url, $current_url] = getUrlInfo();
if ($website_url === '') {
    $website_url = rtrim($base_url, '/');
}

$contentFilePath = handleRoutes();
$og_url = $website_url . '/' . trim(str_replace('content/', '', $contentFilePath), '/');

ob_start();
$page_path = BASE_PATH . '/' . $contentFilePath;
file_exists($page_path) ? include $page_path : include BASE_PATH . '/content/404.php';
$content = ob_get_clean();

include BASE_PATH . '/content/partials/head.php';
include BASE_PATH . '/content/partials/header.php';
echo $content;
include BASE_PATH . '/content/partials/footer.php';
