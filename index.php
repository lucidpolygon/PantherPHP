<?php

$project_name = "Panther PHP";
$meta_title = "PantherPHP - Fast and Light Boilerplate";
$meta_description = "Fast, lightweight PHP boilerplate for efficient web development. Ideal for performance-focused projects.";
$base_url = 'http://localhost:8000/';
$og_image_url = "assets/imgs/og-image.png";


// Check if HTTPS is used, otherwise default to HTTP
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Get the path from the URL
$requestPath = trim($_SERVER['REQUEST_URI'], '/');
if (empty($requestPath)) {
    $requestPath = 'home';
}

// Construct the path to the content file
$contentFilePath = 'content/' . $requestPath . '.php';

// Initialize title with a default value
 // This will be overridden by the included content file, if present

// Use output buffering to capture content file output
ob_start();
if (file_exists($contentFilePath)) {
    include $contentFilePath; // This sets the $title for the page
} else {
    include 'content/404.php';
}
// Get the content from the buffer and clean it
$content = ob_get_clean();

// Now include the head with the dynamically set title
include 'partials/head.php';

// Include header part
include 'partials/header.php';

// Echo the content that was buffered
echo $content;

// Include footer part
include 'partials/footer.php';



?>
