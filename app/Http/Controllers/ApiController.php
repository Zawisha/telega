<?php

namespace App\Http\Controllers;

use App\Models\NotReadyResults;
use App\Models\ReadyResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    protected $notReadyResults;
    protected $readyResults;

    public function __construct(
        NotReadyResults $notReadyResults,
        ReadyResults $readyResults
    )
    {
        $this->notReadyResults=$notReadyResults;
        $this->readyResults=$readyResults;
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
        $response = Http::post('http://hashiro.ru/api/getFromLocal', $data);
        return response()->json([
            'status' => 'success',
            'message' =>'Отправлено',
            'countPosts' =>0,
            'resp' =>$data,
        ], 200);
    }
    public function getFromLocal(Request $request)
    {
        $data = $request->all();
        Storage::put('messages.txt', $data);

//        foreach($data as $oneRow)
//        {
//            $this->readyResults->addResult($oneRow);
//        }
        return response()->json([
            'message' => 'Данные успешно получены',
            'received_data' => 'done'
        ], 200);
    }
}
