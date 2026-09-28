<?php

declare(strict_types=1);

// Stack storage in global scope
$_STACK = [];

function push_to_stack(string $stackName, string $content): void
{
    global $_STACK;
    if (!isset($_STACK[$stackName])) {
        $_STACK[$stackName] = [];
    }
    $_STACK[$stackName][] = $content;
}

function render_stack(string $stackName): string
{
    global $_STACK;
    return isset($_STACK[$stackName]) && !empty($_STACK[$stackName])
        ? implode("\n", $_STACK[$stackName])
        : '';
}

function env(string $name, ?string $default = null): ?string
{
    return $_ENV[$name] ?? getenv($name) ?: $default;
}

function loadEnvVars(string|false|null $envPath): void
{
    if (!$envPath || !file_exists($envPath)) {
        return;
    }

    $envVars = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($envVars === false) {
        return;
    }

    foreach ($envVars as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        $value = trim($value, "\"'");

        $_ENV[$name] = $value;
        putenv($name . '=' . $value);
    }
}

function shouldSkipSitemapFile(SplFileInfo $file): bool
{
    $path = $file->getPathname();
    foreach (['/content/partials/', '/content/draft/', '/content/drafts/'] as $skipPath) {
        if (str_contains($path, $skipPath)) {
            return true;
        }
    }

    $content = file_get_contents($path);
    return $content !== false && preg_match('/\$no_index\s*=\s*true\s*;/', $content) === 1;
}

// Generate sitemap.xml in the project root.
function generateSitemap(): void
{
    [$base_url, ] = getUrlInfo();
    $sitemap = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

    $homepageUrl = rtrim($base_url, '/');
    $homepageElement = $sitemap->addChild('url');
    $homepageElement->addChild('loc', htmlspecialchars($homepageUrl));
    $homepageElement->addChild('lastmod', date('c'));
    $homepageElement->addChild('changefreq', 'daily');
    $homepageElement->addChild('priority', '1.0');

    $directory = new RecursiveDirectoryIterator(BASE_PATH . '/content', FilesystemIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php' || shouldSkipSitemapFile($file)) {
            continue;
        }

        $relativePath = str_replace(BASE_PATH . '/content/', '', $file->getPathname());
        $relativePath = preg_replace('/\.php$/', '', $relativePath);
        $relativePath = str_replace('/index', '', $relativePath);

        if ($relativePath === 'home' || $relativePath === '404') {
            continue;
        }

        $url = rtrim($base_url, '/') . '/' . ltrim((string) $relativePath, '/');
        $urlElement = $sitemap->addChild('url');
        $urlElement->addChild('loc', htmlspecialchars($url));
        $urlElement->addChild('lastmod', date('c', filemtime($file->getPathname())));
        $urlElement->addChild('changefreq', 'monthly');
        $urlElement->addChild('priority', '0.5');
    }

    $sitemap->asXML(BASE_PATH . '/sitemap.xml');
}

// Basic demo handler. Replace with your own provider/API for production forms.
function handleFormSubmission(): void
{
    $formType = isset($_POST['form_type']) ? htmlspecialchars((string) $_POST['form_type']) : '';
    $name = isset($_POST['name']) ? htmlspecialchars((string) $_POST['name']) : '';
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = isset($_POST['message']) ? htmlspecialchars((string) $_POST['message']) : '';
    $errors = [];
    $redirectTo = $_SERVER['HTTP_REFERER'] ?? '/';

    if ($formType === 'newsletter') {
        if (!$email) {
            $errors[] = 'Email is required for newsletter sign-up.';
        }
    } elseif ($formType === 'contact') {
        if (!$name) {
            $errors[] = 'Name is required for contact form.';
        }
        if (!$email) {
            $errors[] = 'Email is required for contact form.';
        }
        if (!$message) {
            $errors[] = 'Message is required for contact form.';
        }
    } else {
        $errors[] = 'Invalid form type submitted.';
    }

    if (empty($errors)) {
        $to = env('CONTACT_TO_EMAIL', 'hello@example.com');
        $subject = $formType === 'newsletter' ? 'Newsletter Sign-up' : 'Contact Form Submission';
        $body = $formType === 'newsletter'
            ? "Newsletter sign-up from: $name <$email>"
            : "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";
        mail((string) $to, $subject, $body, $headers);

        header('Location: ' . $redirectTo . '?success=1');
        exit;
    }

    $query = http_build_query(['errors' => $errors]);
    header('Location: ' . $redirectTo . '?' . $query);
    exit;
}

function getLink(string $name): string
{
    static $links = null;

    if ($links === null) {
        $filePath = BASE_PATH . '/data/links.json';
        $links = file_exists($filePath) ? json_decode((string) file_get_contents($filePath), true) : [];
        if (!is_array($links)) {
            $links = [];
        }
    }

    foreach ($links as $item) {
        if (isset($item['name']) && $item['name'] === $name) {
            return (string) ($item['link'] ?? '#');
        }
    }

    return '#';
}

function render_icon(string $name, string $class = '', string $strokeWidth = ''): void
{
    $safeName = basename($name);
    $iconClass = $class;
    $iconStrokeWidth = $strokeWidth;
    $iconPath = BASE_PATH . "/content/partials/icons/{$safeName}.php";
    if (file_exists($iconPath)) {
        include $iconPath;
    }
}
