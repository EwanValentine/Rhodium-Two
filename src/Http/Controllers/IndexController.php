<?php

namespace MyApp\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rhodium\Http\BaseController;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        return new Response('test');
    }
}
