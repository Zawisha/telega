<?php

namespace App\Http\Controllers;

use App\Models\CheckClient;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    protected $checkClient;

    public function __construct(CheckClient $checkClient)
    {
        $this->checkClient = $checkClient;
    }

    public function index()
    {
        return view('check.main');
    }
    public function checkClient()
    {
        $result=$this->checkClient->checkClientModel(request('inputField'));
        if($result)
        {
            $result='yes';
        }
        else
        {
            $result='no';
            $this->checkClient->addClient(request('inputField'));
        }
        return back()->with('result', $result);
    }
}
