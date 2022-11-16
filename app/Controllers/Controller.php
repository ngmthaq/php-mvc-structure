<?php

namespace App\Controllers;

use Core\Request;

abstract class Controller
{
    protected Request $req;

    public function __construct(Request $request)
    {
        $this->req = $request;
    }
}
