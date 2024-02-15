<?php

// Project configuration settings
$project_name = "PHP Boilerplate";
$meta_title = "PHP Boilerplate";
$meta_description = "PHP Boilerplate.";
$base_url = 'http://localhost:8000/';
$og_image_url = "assets/imgs/og-image.png";

// Establishing the base path for consistent file inclusion
define('BASE_PATH', realpath(__DIR__));

// Determining the protocol (HTTP or HTTPS) to ensure proper URL formation
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Parsing the request URI to identify the requested path
$requestPath = trim($_SERVER['REQUEST_URI'], '/');
$requestPath = empty($requestPath) ? 'home' : $requestPath;

// Handling nested directories to route requests dynamically
$pathParts = explode('/', $requestPath);
$contentFilePath = 'content/';
foreach ($pathParts as $index => $part) {
    $contentFilePath .= $part;
    // Append directory separator for intermediate parts or file extension for the final part
    $contentFilePath .= ($index < count($pathParts) - 1) ? '/' : (is_dir($contentFilePath) ? '/index.php' : '.php');
}

// Output buffering to capture dynamic content for flexible title management
ob_start();
if (file_exists($contentFilePath)) {
    include BASE_PATH.'/'.$contentFilePath; // Dynamically include the content file based on the request
} else {
    include BASE_PATH.'/content/404.php'; // Fallback to a custom 404 page if the file does not exist
}
$content = ob_get_clean(); // Retrieve and clean the buffer

// Including template parts with dynamic content and titles
include BASE_PATH.'/partials/head.php'; // Head section including meta tags and titles
include BASE_PATH.'/partials/header.php'; // Site header
echo $content; // Display the main content area
include BASE_PATH.'/partials/footer.php'; // Site footer

?>
