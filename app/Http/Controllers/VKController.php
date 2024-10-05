<?php

namespace App\Http\Controllers;

use App\Models\Vksettings;
use Illuminate\Http\Request;

class VKController extends Controller
{
    protected $vksettings;

    public function __construct(
        Vksettings $vksettings,
    )
    {
        $this->vksettings=$vksettings;
    }

    public function index()
    {
        $account=$this->vksettings->getFirst();
        $transfer=[
            'account' => $account,
        ];
        return view('vk.main', ['transfer' => $transfer]);
    }
    public function updateTokenVK()
    {
        $this->vksettings->upToken(request('account')['account_info'],request('account')['token']);
        return response()->json([
            'status' => 'success',
            'message' =>'Обновил значение',
        ], 200);
    }
    public function getPosts($groups)
    {
        //dd($groups[0]['group_name']);
        $token=$this->vksettings->getToken();
        $xml=[];
        foreach($groups as $oneGroup)
        {
        //получить список постов
            try {
                $xml[] = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-".$oneGroup['group_name']."&count=5&v=5.131&access_token=".$token));
            }
            catch (\Throwable $e)
            {
                return response()->json([
                    'status' => 'ошибка в получении списка постов группы',
                    'error'    =>  $e,
                    'group'    =>  $group_id,
                ], 200);
            }
        }
        return $xml;
    }
}
//$xml = json_decode(file_get_contents("https://api.vk.com/method/wall.getById?posts=-" . '99357794_38524' . "&v=5.131&access_token=".$token));
//$post_text=$xml->response['0']->text;
