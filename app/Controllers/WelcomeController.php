<?php

namespace App\Controllers;

use App\Helpers\Console;
use App\Models\User;
use Core\Request;

class WelcomeController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user = new User();
    }

    public function index()
    {
        $user = $this->user->find(2);
        Console::log($user);

        return view("welcome");
    }

    public function json()
    {
        return response()->json(['message' => 'Welcome']);
    }
}
