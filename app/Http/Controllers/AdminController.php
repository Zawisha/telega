<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddClientRequest;
use App\Models\MyClient;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    protected $myClient;

    public function __construct(MyClient $myClient)
    {
        $this->myClient = $myClient;
    }

    public function addClient(AddClientRequest $addClientRequest)
    {
        $this->myClient->addNewClient(request('name'),request('desc'));
        return response()->json([
            'status' => 'success',
            'message' =>'Новый клиент создан',
        ], 200);
    }
}
