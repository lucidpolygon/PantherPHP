# Project Structure: PantherPHP

```
/Users/carla/Herd/PantherPHP
├── assets/                  # Static assets for the project
│   ├── css/                 # Stylesheets (Tailwind CSS input/output files)
│   │   ├── input.css
│   │   └── output.css       # Compiled Tailwind CSS file
│   └── imgs/                # Images used in the project
├── content/                 # Dynamic content pages
│   ├── 404.php              # Custom 404 error page
│   ├── about.php            # About page
│   ├── contact.php          # Contact page with a form
│   └── home.php             # Home page
├── logs/                    # Directory for error logs
├── partials/                # Reusable partial templates
│   ├── footer.php           # Footer section
│   ├── head.php             # Head section with meta tags and SEO
│   └── header.php           # Header section with navigation
├── src/                     # Core PHP functions
│   └── functions.php        # Helper functions for routing, sitemap, and form handling
├── .env                     # Environment configuration file
├── .gitignore               # Git ignore rules
├── .htaccess                # Apache configuration for routing and security
├── index.php                # Main entry point for the application
├── package.json             # Node.js package configuration for Tailwind CSS
├── readme.md                # Project documentation
├── sitemap.xml              # Sitemap for SEO (auto-generated)
└── tailwind.config.js       # Tailwind CSS configuration
```

## Key Highlights
- **`content/`**: Contains dynamic PHP pages for the website (e.g., `home.php`, `about.php`).
- **`partials/`**: Reusable components like `header.php`, `footer.php`, and `head.php`.
- **`src/functions.php`**: Core logic for routing, sitemap generation, and form handling.
- **`index.php`**: Main entry point that handles routing and includes partials dynamically.
- **`assets/css/`**: Tailwind CSS setup with `input.css` and compiled `output.css`.
- **`.env`**: Stores environment variables like project name, meta tags, and base URL.
- **`.htaccess`**: Configures URL rewriting and restricts access to sensitive files.
- **`sitemap.xml`**: Auto-generated sitemap for SEO purposes.

This structure is designed for a lightweight PHP project with dynamic content loading, SEO optimization, and Tailwind CSS integration. It avoids complex frameworks, making it easy to deploy and maintain.