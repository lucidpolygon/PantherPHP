# PantherPHP

Minimal PHP boilerplate for small, fast, content-led websites.

This is designed for the kind of sites that do not need a framework: landing pages, brochure sites, simple blogs, service pages, and small content hubs that can be deployed to ordinary PHP hosting.

## What it gives you

- File-based routing from `/content/*.php`
- Nested routes with `content/folder/index.php`
- Shared partials for head/header/footer
- Per-page meta title, description, canonical URL, OG image, and `noindex`
- Tailwind CSS 4 build/watch scripts
- Cache-busted CSS output via file modified time
- Redirects from `data/redirects.json`
- Sitemap generation at `/generate-sitemap`
- Draft/private folder blocking, with optional preview token
- Small helper stacks for per-page CSS/JS
- Simple `.env` config with safe defaults

## Requirements

- PHP 8+
- Node.js/npm only if you want to rebuild Tailwind CSS

## Quick start

```bash
cp .env.example .env
npm install
npm run build
php -S localhost:8080
```

Open <http://localhost:8080>.

## Project structure

```text
assets/
  css/input.css       Tailwind source
  css/output.css      Generated CSS
  imgs/               Images and social previews
content/
  home.php            Homepage route `/`
  about.php           `/about`
  blog.php            `/blog`
  blog/article1.php   `/blog/article1`
  partials/           Shared template partials, not routable
  draft/              Draft pages, blocked unless preview token is used
data/
  redirects.json      Redirect map
  links.json          Named links helper data
src/
  functions.php       Helpers, env, sitemap, forms
  route.php           Request routing
index.php             Front controller
```

## Add a page

Create `content/services.php`:

```php
<?php
$meta_title = 'Services | ' . $project_name;
$meta_description = 'What we can help with.';
$og_image_url = 'assets/imgs/services-og.png';
?>

<main>
  <h1>Services</h1>
</main>
```

It is available at `/services`.

For nested pages, create `content/services/audit.php` and visit `/services/audit`.

## Per-page CSS or JS

```php
<?php
push_to_stack('custom-css', '<link rel="stylesheet" href="/assets/css/services.css">');

$script = <<<HTML
<script>
  console.log('Page script');
</script>
HTML;
push_to_stack('custom-js', $script);
?>
```

`custom-css` is rendered in the `<head>`. `custom-js` is rendered in `content/partials/footer.php`.

## Redirects

Edit `data/redirects.json`:

```json
{
  "old-page": {
    "to": "/new-page",
    "status": 301
  },
  "temporary-offer": "/current-offer"
}
```

Supported status codes: `301` and `302`.

## Draft previews

Files under `content/draft/` and `content/drafts/` are not public.

Set `PREVIEW_TOKEN` in `.env`, then visit:

```text
/draft/my-page?preview=your-token
```

## Sitemap

Visit `/generate-sitemap` to write `sitemap.xml`.

The sitemap skips:

- `content/partials/`
- `content/draft/` and `content/drafts/`
- `content/home.php` because it is represented by `/`
- `content/404.php`
- pages containing `$no_index = true;`

## Tailwind CSS

```bash
npm run watch   # during development
npm run build   # normal build
npm run minify  # minified production CSS
```

## Deployment

Upload the project to any PHP-capable host. For production, copy `.env.example` to `.env`, set your real values, run `npm run minify`, and deploy the generated `assets/css/output.css` with the PHP files.

## License

MIT. Free for personal and commercial use.
