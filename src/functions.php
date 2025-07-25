<?php

// Stack storage in global scope
$_STACK = [];

// Push content to a named stack (e.g., 'custom-js')
function push_to_stack($stackName, $content) {
    global $_STACK;
    if (!isset($_STACK[$stackName])) {
        $_STACK[$stackName] = [];
    }
    $_STACK[$stackName][] = $content;
}

// Render the contents of a named stack
function render_stack($stackName) {
    global $_STACK;
    if (isset($_STACK[$stackName]) && !empty($_STACK[$stackName])) {
        return implode("\n", $_STACK[$stackName]);
    }
    return '';
}

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

// Generate Sitemap and save it to the main directory
function generateSitemap()
{
    // Determine the base URL for the sitemap
    list($base_url, ) = getUrlInfo();
    
    // Initialize the XML structure for the sitemap
    $sitemap = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

    // Add the homepage to the sitemap
    $homepageUrl = rtrim($base_url, '/');
    $homepageElement = $sitemap->addChild('url');
    $homepageElement->addChild('loc', htmlspecialchars($homepageUrl));
    $homepageElement->addChild('lastmod', date('c')); // Use the current date for the homepage
    $homepageElement->addChild('changefreq', 'daily'); // Typically, the homepage is updated frequently
    $homepageElement->addChild('priority', '1.0'); // Highest priority
    
    // Recursively iterate through the content directory
    $directory = new RecursiveDirectoryIterator(BASE_PATH . '/content');
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $relativePath = str_replace(BASE_PATH . '/content/', '', $file->getPathname());
            $relativePath = str_replace('.php', '', $relativePath);

            // Convert the relative path to a URL
            $url = rtrim($base_url, '/') . '/' . ltrim($relativePath, '/');
            
            // Add the URL to the sitemap
            $urlElement = $sitemap->addChild('url');
            $urlElement->addChild('loc', htmlspecialchars($url));
            $urlElement->addChild('lastmod', date('c', filemtime($file->getPathname())));
            $urlElement->addChild('changefreq', 'monthly');
            $urlElement->addChild('priority', '0.5');
        }
    }

    // Save the sitemap XML to the main directory
    $sitemapFilePath = BASE_PATH . '/sitemap.xml';
    $sitemap->asXML($sitemapFilePath);
}

//Handle Newsletter signups and Contact Submissions
function handleFormSubmission() {
    $formType = isset($_POST['form_type']) ? htmlspecialchars($_POST['form_type']) : '';
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';
    $errors = [];
    $redirectTo = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; // Fallback to home if HTTP_REFERER not set
    // Validate based on form type
    if ($formType === 'newsletter') {
        if (!$email) $errors[] = 'Email is required for newsletter sign-up.';
    } elseif ($formType === 'contact') {
        if (!$name) $errors[] = 'Name is required for contact form.';
        if (!$email) $errors[] = 'Email is required for contact form.';
        if (!$message) $errors[] = 'Message is required for contact form.';
    } else {
        $errors[] = 'Invalid form type submitted.';
    }

    if (empty($errors)) {
        $to = 'xyz@abc.com';
        $subject = $formType === 'newsletter' ? 'Newsletter Sign-up' : 'Contact Form Submission';
        $body = $formType === 'newsletter' ? "Newsletter sign-up from: $name <$email>" : "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";
        mail($to, $subject, $body, $headers); // Ensure your server is configured to send mail

        // Redirect back with success
        header('Location: ' . $redirectTo . '?success=1');
        exit;
    } else {
        // Redirect back with errors
        $query = http_build_query(['errors' => $errors]);
        header('Location: ' . $redirectTo . '?' . $query);
        exit;
    }
}
