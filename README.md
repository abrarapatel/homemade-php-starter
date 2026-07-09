# 🚀 Minimalist PHP MVC Starter

A lightweight, high-performance PHP framework designed for small business websites and rapid prototyping. It follows a clean MVC-like architecture without the overhead of heavy third-party dependencies.

## ✨ Key Features

- **Simple Routing**: Easy-to-manage configuration in `app/config/routes.php` supporting both page and API routes
- **MVC Architecture**: Clean separation of concerns with dedicated Controllers and Views
- **Dynamic Layouts**: Built-in support for master layouts (`main.php`) with easy content injection
- **Asset Management**: Flexible system for loading page-specific CSS and JS resources from controllers
- **SEO Ready**: Clean URLs via `.htaccess` and dynamic meta tag management (Title/Description)
- **Simple Autoloading**: PSR-4 style namespaced autoloading for clean code organization
- **Lightweight**: Zero external dependencies—runs fast on standard XAMPP/LAMP environments

## 📁 Project Structure

```
minimalist-php-mvc/
├── app/
│   ├── config/
│   │   └── routes.php           # Route definitions
│   ├── Controllers/             # Controller classes
│   └── views/                   # View templates
│       └── layouts/
│           └── main.php         # Master layout template
└── public_html/                 # Web root directory
    ├── assets/
    │   ├── images/              # Static images
    │   ├── icons/               # Icon files
    │   └── favicons/            # Favicon files
    ├── css/
    │   ├── global.css           # Global styles
    │   └── pages/               # Page-specific styles
    ├── js/
    │   ├── global.js            # Global scripts
    │   └── pages/               # Page-specific scripts
    ├── .htaccess                # URL rewriting configuration
    └── index.php                # Main entry point and router
```

## 🛠️ Quick Start

### Prerequisites

- PHP 7.4 or higher
- Apache with mod_rewrite enabled
- XAMPP, LAMP, or similar local server environment

### Installation

1. **Clone the repository** into your web directory:
   ```bash
   git clone https://github.com/abrarapatel/homemade-php-starter.git
   cd minimalist-php-mvc
   ```

2. **Configure your routes** in `app/config/routes.php`:
   ```php
   <?php
   return [
       '/' => 'HomeController@index',
       '/about' => 'AboutController@index',
       '/contact' => 'ContactController@index',
   ];
   ```

3. **Create your first controller** in `app/Controllers/`:
   ```php
   <?php
   namespace App\Controllers;

   class HomeController {
       public function index() {
           return [
               'view' => 'home',
               'data' => [
                   'title' => 'Welcome',
                   'message' => 'Hello World!'
               ]
           ];
       }
   }
   ```

4. **Create your view** in `app/views/`:
   ```php
   <!-- app/views/home.php -->
   <h1><?= $title ?></h1>
   <p><?= $message ?></p>
   ```

5. **Start building!** Navigate to `http://localhost/homemade-php-starter/` in your browser.

## 📖 Usage Guide

### Routing

Define routes in `app/config/routes.php`:

```php
<?php
return [
    // Page routes
    '/' => 'HomeController@index',
    '/about' => 'AboutController@index',
    '/products' => 'ProductController@list',
    '/products/{id}' => 'ProductController@show',
    
    // API routes
    '/api/users' => 'Api\UserController@index',
    '/api/users/{id}' => 'Api\UserController@show',
];
```

### Controllers

Create controllers in `app/Controllers/`:

```php
<?php
namespace App\Controllers;

class ProductController {
    
    public function list() {
        return [
            'view' => 'products/list',
            'data' => [
                'title' => 'Our Products',
                'products' => $this->getProducts()
            ],
            'css' => ['pages/products.css'],
            'js' => ['pages/products.js']
        ];
    }
    
    public function show($id) {
        return [
            'view' => 'products/detail',
            'data' => [
                'title' => 'Product Details',
                'product' => $this->getProduct($id)
            ]
        ];
    }
    
    private function getProducts() {
        // Your data logic here
        return [];
    }
}
```

### Views

Create views in `app/views/`:

```php
<!-- app/views/products/list.php -->
<div class="products-container">
    <h1><?= $title ?></h1>
    
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <a href="/products/<?= $product['id'] ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
```

### Asset Management

Load page-specific assets from your controller:

```php
return $this->view('home', [
    'title' => 'Starter Project',
    'description' => 'A starter template.',
    'headerResources' => [
        'css' => ['css/home.css']
    ],
    'footerResources' => [
        'js' => ['js/home.js']
    ]
]);
```

### API Routes

Create API controllers for JSON responses:

```php
<?php
namespace App\Controllers\Api;

class UserController {
    
    public function index() {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getUsers()
        ]);
        exit;
    }
    
    public function show($id) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getUser($id)
        ]);
        exit;
    }
}
```

## 📄 License

This project is open-source and available under the MIT License.

## 🙏 Acknowledgments

Built with simplicity and performance in mind for small businesses and rapid development.

---

**Happy Coding!** 🎉
