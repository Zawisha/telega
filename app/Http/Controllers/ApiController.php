<?php

namespace App\Http\Controllers;

use App\Models\NotReadyResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    protected $notReadyResults;

    public function __construct(
        NotReadyResults $notReadyResults
    )
    {
        $this->notReadyResults=$notReadyResults;
    }

    public function index()
    {
        $countResultes=$this->notReadyResults->getCount();
        $transfer=[
            'countResultes' => $countResultes,
        ];
        return view('search.sendHosting', ['transfer' => $transfer]);
    }
    public function sendToHosting()
    {
        $data=$this->notReadyResults->getFiveRows();

        // Выполнение POST-запроса
        $response = Http::post('https://hashiro.ru/api/getFromLocal', $data);
        return response()->json([
            'status' => 'success',
            'message' =>'Собрал группы',
            'countPosts' =>0,
            'resp' =>$response,
        ], 200);
    }
    public function getFromLocal(Request $request)
    {
        $data = $request->all();
        return response()->json([
            'message' => 'Данные успешно получены',
            'received_data' => $data
        ], 200);
    }
}
