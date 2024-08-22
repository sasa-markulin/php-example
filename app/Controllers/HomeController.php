<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return void
     */
    public function index()
    {
        $this->view('Home');
    }
}
