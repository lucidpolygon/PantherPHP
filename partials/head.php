<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <link href="<?php echo($base_url) ?>assets/css/output.css" rel="stylesheet">    

    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="title" content="<?php echo htmlspecialchars($meta_title); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>" />
    
    <link rel="icon" type="image/png" href="<?php echo($base_url).'assets/imgs/logo.png' ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($current_url); ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo htmlspecialchars($current_url); ?>" />
    <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>" />
    <meta property="og:image" content="<?php echo isset($og_image_url) ? htmlspecialchars($base_url.$og_image_url) : ''; ?>" />


    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="<?php echo htmlspecialchars($current_url); ?>" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>" />
    <meta name="twitter:image" content="<?php echo isset($og_image_url) ? htmlspecialchars($base_url.$og_image_url) : ''; ?>" />

</head>

<body>