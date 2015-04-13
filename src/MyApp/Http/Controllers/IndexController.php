<?php

namespace MyApp\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Rhodium\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        return new Response('test');
    }
}
