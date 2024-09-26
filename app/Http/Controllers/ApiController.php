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
        $data=$this->notReadyResults->getFiveRows();
        // Выполнение POST-запроса
//        $response = Http::post('http://hashiro.ru/api/getFromLocal', $data);
// Выполнение POST-запроса с передачей JSON данных
        $data = json_encode([
            'name' => 'John Doe111',
            'email' => 'john@example.com'
        ]);
        $options = ['http' => [
            'method' => 'POST',
            'header' => 'Content-type:application/json',
            'content' => $data
        ]];
        $context = stream_context_create($options);
        $response = file_get_contents('http://hashiro.ru/api/getFromLocal', false, $context);

        return response()->json([
            'status' => 'success',
            'message' =>'Отправлено',
            'countPosts' =>0,
            'resp' =>$data,
            'response' =>$response,
        ], 200);
    }
    public function getFromLocal(Request $request)
    {
       // $data = $request->data;
       // $req = $request->getContent();
       // $rawData = parse_str($request);

      //  $str = file_get_contents('php://input') . PHP_EOL;
        try {
            Storage::put('messages12.txt', $request);
        }
        catch(\Exception $e) {
            Storage::put('messages9.txt', $e);
        }
        //Storage::put('messages4.txt', $data[0]);
        //Storage::put('messages1.txt', json_encode($data, JSON_PRETTY_PRINT));
        //$data=json_encode($data, JSON_PRETTY_PRINT);
//        foreach($data as $oneRow)
//        {
//            $this->readyResults->test($oneRow);
//        }
        return response()->json([
            'message' => 'Данные успешно получены',
            'received_data' => 'done'
        ], 200);
    }
}
