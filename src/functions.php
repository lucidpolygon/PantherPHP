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
        $to = 'thaha@lucidpolygon.com';
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
