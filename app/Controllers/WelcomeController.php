<?php

namespace App\Controllers;

use App\Helpers\Console;
use Core\Request;

class WelcomeController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        return view("welcome");
    }

    public function json()
    {
        return response()->json(['message' => 'Welcome']);
    }
}
