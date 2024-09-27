<?php

namespace App\Http\Controllers;

use App\Models\NotReadyResults;
use App\Models\ReadyResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        sleep(2);
        $data=$this->notReadyResults->getFiveRows();
        $dataSend=json_encode($data);
        // Выполнение POST-запроса удачно
        $options = ['http' => [
            'method' => 'POST',
            'header' => 'Content-type:application/json',
            'content' => $dataSend
        ]];
        $context = stream_context_create($options);
        $response = file_get_contents('http://hashiro.ru/api/getFromLocal', false, $context);
        //отмечаем те посты что передавал
        foreach($data as $oneRow)
        {
            $this->notReadyResults->setPeredano($oneRow['id']);
        }
        //считаем количество оставшихся постов
        $countPosts=$this->notReadyResults->getCount();

        return response()->json([
            'status' => 'success',
            'message' =>'Отправлено',
            'countPosts' =>$countPosts,
            'response' =>$response,
        ], 200);
    }
    public function getFromLocal(Request $request)
    {
        try {
            $data = $request->json()->all();
        }
        catch(\Exception $e) {
           return $e;
        }
        foreach($data as $oneRow)
        {
            $this->notReadyResults->addResult($oneRow);
        }
        return response()->json([
            'message' => 'Данные успешно получены',
            'received_data' => 'done'
        ], 200);
    }
}
