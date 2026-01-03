<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // phpinfo();
        return view('welcome_message');
    }
}
