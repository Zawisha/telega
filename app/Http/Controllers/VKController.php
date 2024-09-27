<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VKController extends Controller
{
    public function index()
    {
        return view('vk.main');
    }
}
