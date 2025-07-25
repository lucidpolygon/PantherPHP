<?php
// Access query parameters defined in route.php
$queryParams = defined('QUERY_PARAMS') ? QUERY_PARAMS : [];
$tab = $queryParams['tab'] ?? 'default';

echo "<h1>About Us</h1>";
if ($tab === 'team') {
    echo "<p>Meet our team!</p>";
} else {
    echo "<p>Welcome to the about page.</p>";
}