<?php
// app/tools/page-generator.php

function generatePage($name, $slug, $title, $description)
{
    $controllerName = str_replace(' ', '', ucwords($name)) . 'Controller';
    $viewName = strtolower(str_replace(' ', '-', $name));
    $slug = '/' . ltrim($slug, '/');

    // 1. Create Controller
    $controllerTemplate = "<?php
namespace App\Controllers;

use App\Core\Controller;

class $controllerName extends Controller {
    public function index() {
        return \$this->view('$viewName', [
            'title' => '$title',
            'description' => '$description'
        ]);
    }
}
";
    file_put_contents(__DIR__ . "/../controllers/$controllerName.php", $controllerTemplate);

    // 2. Create View
    $viewTemplate = "<h1>$name</h1>\n<p>Content for $name goes here.</p>";
    file_put_contents(__DIR__ . "/../views/$viewName.php", $viewTemplate);

    // 3. Register Route in app/config/routes.php
    $routesFile = __DIR__ . "/../config/routes.php";
    $routes = require $routesFile;
    $routes['pages'][$slug] = "$controllerName@index";

    $routesExport = "<?php\n\nreturn " . var_export($routes, true) . ";\n";
    file_put_contents($routesFile, $routesExport);

    echo "Successfully generated page: $name\n";
    echo "Controller: app/controllers/$controllerName.php\n";
    echo "View: app/views/$viewName.php\n";
    echo "Route: $slug -> $controllerName@index\n";
}

// Simple CLI usage: php page-generator.php "About Us" "about-us" "About Our Company" "Learn more about us."
if (isset($argv[1])) {
    generatePage($argv[1], $argv[2], $argv[3], $argv[4]);
}
