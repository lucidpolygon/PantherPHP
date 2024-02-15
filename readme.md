# PantherPHP - Light and Fast PHP Boilerplate for your next website.

## Overview

A lightweight PHP boilerplate for building websites. Features dynamic content loading, SEO optimization capabilities, and Tailwind CSS integration for styling.

Simply populate the content directory with your files and deploy the entire folder to your hosting environment via FTP. Boom! website is live. 

## Structure

- `content/`: Add new `.php` pages here.
- `partials/`: Contains site-wide headers and footers.
- `assets/css/`: Place Tailwind CSS input file here.
- `assets/imgs/`: Place Images here.

## Setup

1. **Clone or Download**: Get the boilerplate onto your local machine.
2. **Add Pages**: Place new `.php` files within the `content/` directory.
3. **Configure Project Settings**: Update the project-specific settings in `index.php`, including project name, base URL, and default meta tags.
4. **Override Meta Tags**: In individual content files, specify custom `$meta_title`, `$meta_description`, and `$og_image_url` to tailor SEO for each page.


## SEO

Set unique meta tags for each page in its respective content file for better search engine visibility.

## Tailwind CSS

Compile Tailwind CSS with:

```
npx tailwindcss -i ./assets/css/input.css -o ./assets/css/output.css --watch
```

## Contributing

Fork, make your changes, and submit a pull request to contribute.

## License

MIT License. Free for both personal and commercial use.