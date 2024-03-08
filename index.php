<?php

require_once __DIR__ . '/src/functions.php';

$envPath = realpath(__DIR__ . '/.env');
loadEnvVars($envPath);

// Project configuration settings
$project_name = $_ENV['PROJECT_NAME'];
$meta_title = $_ENV['META_TITLE'];
$meta_description = $_ENV['META_DESCRIPTION'];
$og_image_url = $_ENV['OG_IMAGE_URL'];
$no_index = false;

// Establishing the base path for consistent file inclusion
define('BASE_PATH', realpath(__DIR__));
list($base_url, $current_url) = getUrlInfo();
$contentFilePath = getContentFilePath();

ob_start();
$page_path = BASE_PATH . '/' . $contentFilePath;
file_exists($page_path) ? include $page_path : include BASE_PATH . '/content/404.php';
$content = ob_get_clean();

// Including template parts with dynamic content and titles
include BASE_PATH.'/partials/head.php'; // Head section including meta tags and titles
include BASE_PATH.'/partials/header.php'; // Site header
echo $content; // Display the main content area
include BASE_PATH.'/partials/footer.php'; // Site footer

?>
