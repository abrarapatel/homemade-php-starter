<?php
// app/controllers/HomeController.php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
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
    }
}
