<?php
// app/core/Controller.php

namespace App\Core;

class Controller
{
    protected function view($view, $data = [], $layout = 'main')
    {
        extract($data);

        // Start buffering for the view content
        ob_start();
        require __DIR__ . "/../views/$view.php";
        $content = ob_get_clean();

        // Include the layout, which uses $content
        require __DIR__ . "/../views/layouts/$layout.php";
    }

    protected function json($data, $status = 200)
    {
        Response::json($data, $status);
    }
}
