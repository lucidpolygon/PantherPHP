<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="<?php echo htmlspecialchars($base_url . 'assets/css/output.css?v=' . ($asset_version ?? time())); ?>" rel="stylesheet">
  <?php echo render_stack('custom-css'); ?>

  <!-- Primary Meta Tags -->
  <title><?php echo htmlspecialchars($meta_title); ?></title>
  <meta name="title" content="<?php echo htmlspecialchars($meta_title); ?>" />
  <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>" />

  <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($base_url . 'assets/imgs/logo.png'); ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url ?? $current_url); ?>" />
  <?php echo $no_index ? '<meta name="robots" content="noindex">' : ''; ?>

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo htmlspecialchars($current_url); ?>" />
  <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>" />
  <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>" />
  <meta property="og:image" content="<?php echo htmlspecialchars($base_url . ltrim($og_image_url ?? '', '/')); ?>" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:url" content="<?php echo htmlspecialchars($current_url); ?>" />
  <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>" />
  <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>" />
  <meta name="twitter:image" content="<?php echo htmlspecialchars($base_url . ltrim($og_image_url ?? '', '/')); ?>" />

  <?php if (!empty($json_ld ?? null)): ?>
  <script type="application/ld+json"><?php echo $json_ld; ?></script>
  <?php endif; ?>

  <?php if (!empty($ga4_id)): ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($ga4_id); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo htmlspecialchars($ga4_id); ?>');
  </script>
  <?php endif; ?>
</head>

<body>
