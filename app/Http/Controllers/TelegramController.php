<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddGroupRequest;
use App\Http\Requests\AddTelegramUsersToGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\GetCountTelegramRequest;
use App\Http\Requests\JoinGroupRequest;
use App\Http\Requests\SaveNewTelegramUserRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\StartInviteTelegramRequest;
use App\Http\Requests\TelegramCodeRequest;
use App\Http\Requests\UpOneLineTelegramRequest;
use App\Models\OneClientTelegramLine;
use App\Models\TelegramGroups;
use App\Models\TelegramInviteUsers;
use App\Models\TelegramPhones;
use App\Models\TelegramUsers;
use App\Services\TelegramService;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\SessionMaddelineTrait;

class TelegramController extends Controller
{
    use SessionMaddelineTrait;


    protected $TelegramPhones;
    protected $TelegramGroups;
    protected $TelegramUsers;
    protected $TelegramInviteUsers;
    protected $telegramService;
    protected $oneClientTelegramLine;

    public function __construct(
        TelegramPhones $TelegramPhones,
        TelegramGroups $TelegramGroups,
        TelegramUsers $TelegramUsers,
        TelegramInviteUsers $TelegramInviteUsers,
        TelegramService $telegramService,
        OneClientTelegramLine $oneClientTelegramLine
        )
    {
        $this->TelegramPhones=$TelegramPhones;
        $this->TelegramGroups=$TelegramGroups;
        $this->TelegramUsers=$TelegramUsers;
        $this->TelegramInviteUsers=$TelegramInviteUsers;
        $this->telegramService=$telegramService;
        $this->oneClientTelegramLine=$oneClientTelegramLine;
    }

    public function index()
    {
        return view('telegram.main');
    }

    public function startInvite(StartInviteTelegramRequest $startInviteTelegramRequest)
    {
        $count=0;
        $phone=$this->TelegramPhones->getPhoneById(request('phone_id'));
        $group=$this->TelegramGroups->getGroupById(request('group_id'));
        $MadelineProto=$this->madAuth($phone,'not');
        //цикл в 150 повторений сделан для предохранителя
        for($i = 0; $i < 150; $i++)
        {
            if($count<40)
            {
            sleep(30);
        //получаем пользователя
        $userForInvite=$this->telegramService->getUserForInvite(request('group_id'));
        //если пользователь получен ( не закончилась БД )
        if($userForInvite['success'])
        {
            //сам инвайт
            try {
                $messages_InvitedUsers = $MadelineProto->channels->inviteToChannel(channel: $group, users:[$userForInvite['user']->user_name] );
                //тут решаем увеличивать ли счётчик
                $count=$this->telegramService->processingInviteRespSuc($messages_InvitedUsers, $count);
            }
            //тут всё что точно не добавлено
            catch (\Exception $e)
            {
                $message=$e->getMessage();
                $fail_arr=$this->telegramService->processingInviteRespFail($message,$phone);
                //если критическая ошибка
                if($fail_arr['crytical'])
                {
                    return response()->json([
                        'status' => 'success',
                        'countUsers' => $count,
                        'localStatus' => 'crytical_FAIL',
                        'message' =>$fail_arr['message'],
                    ], 200);
                }
            }
        }
        //если закончилась база для инвайта
        else
        {
            return response()->json([
                'status' => 'success',
                'countUsers' => $count,
                'message' =>$userForInvite['message'].'Сделал: '.$count,
            ], 200);
        }
            }
            else
            {
                return response()->json([
                    'status' => 'success',
                    'countUsers' => $count,
                    'localStatus' => 'success',
                    'message' =>'Рассылка окончена.'.' Сделал: '.$count,
                ], 200);
            }

        }
        return response()->json([
            'status' => 'success',
            'countUsers' => $count,
            'localStatus' => 'success',
            'message' =>'Превышен счётчик предохранителя',
        ], 200);

    }

    public function inviteTelegram()
    {
        $lines=$this->telegramService->getFullLines();
        $groups=$this->TelegramGroups->getAll();
        $phones=$this->TelegramPhones->getAllPhones();
        $transfer=[
            'lines' => $lines,
            'groups' => $groups,
            'phones' => $phones,
        ];
        return view('telegram.invite', ['transfer' => $transfer]);
    }
    public function databaseTelegram()
    {
        return view('telegram.database');
    }
    public function nameGroup()
    {
        $groups=$this->TelegramGroups->getAll();
        $transfer=[
            'groups' => $groups,
        ];
        return view('telegram.nameGroup', ['transfer' => $transfer]);
    }
    public function dbTelegram()
    {
        $groups=$this->TelegramGroups->getAll();
        $transfer=[
            'groups' => $groups,
        ];
        return view('telegram.dbTelegram', ['transfer' => $transfer]);
    }
    public function saveNewTelegramUser(SaveNewTelegramUserRequest $requestVal)
    {
        //использую request для валидации и передаю валидированные параметры в метод модели
        $this->TelegramPhones->createPhone($requestVal);
        return response()->json([
            'status' => 'success',
            'message' =>'Телефон успешно добавлен',
        ], 200);
    }
    public function getAuthCodeTelegram(TelegramCodeRequest $telegramCodeRequest)
    {
        //получаю переменную телефон из валидации
        $phone=$telegramCodeRequest['phone'];
        //Подключаем сессию
        $MadelineProto=$this->madAuth($phone,'new');
        //Запрашиваем код
        $MadelineProto->phoneLogin($phone);


        return response()->json([
            'status' => 'success',
            'message' =>'Введите код авторизации',
        ], 200);
    }

    public function sendCode(SendCodeRequest $sendCodeRequest)
    {
        $MadelineProto=$this->madAuth(request('phone'),'not');
        try {
            $authorization = $MadelineProto->completePhoneLogin(request('authCode'));
            $MadelineProto->start();
            return response()->json([
                'status' => 'success',
                'message' =>'Аккаунт успешно авторизован',
                'auth' =>$authorization,
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function joinToGroup(JoinGroupRequest $joinGroupRequest)
    {
        $MadelineProto=$this->madAuth(request('phone'),'not');
        try {
            $MadelineProto->channels->joinChannel(['channel' => request('groupAdress')]);
            return response()->json([
                'status' => 'success',
                'message' =>'Вступил в группу',
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function addGroup(AddGroupRequest $addGroupRequest)
    {
       $newGroup=$this->TelegramGroups->saveNewGroup(request('group_name'));
        return response()->json([
            'status' => 'success',
            'message' =>'Группа сохранена',
            'group' =>$newGroup,
        ], 200);
    }

    public function deleteGroup(DeleteGroupRequest $deleteGroupRequest)
    {
        //ТУТ ДОПИСАТЬ УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЕЙ ПРИВЯЗАННЫХ И УДАЛЕНИЕ СТРОК С ЭТОЙ ГРУППОЙ ДЛЯ РАССЫЛКИ
        $this->TelegramGroups->deleteGroup(request('id'));
        return response()->json([
            'status' => 'success',
            'message' =>'Группа удалена',
        ], 200);
    }
    public function deleteLine()
    {
        $this->TelegramInviteUsers->deleteOneLine(request('id'));
        return response()->json([
            'status' => 'success',
            'message' =>'Строка удалена',
        ], 200);
    }
    public function addTelegramUsersToGroup(AddTelegramUsersToGroupRequest $addTelegramUsersToGroupRequest)
    {
        $lines = preg_split('/\r\n|\r|\n/', request('users'));
        foreach ($lines as $line) {
            if (!empty($line)) { // Проверяем, что строка не пустая
                $this->TelegramUsers->addUserToDB($line,request('group_id'));
            }
        }
    }
    public function addStrokaTelegramInvite()
    {
       $newLine=$this->TelegramInviteUsers->addLine();
        return response()->json([
            'status' => 'success',
            'message' =>'Строка добавлена',
            'newLine' =>$newLine,
        ], 200);
    }
    public function upDataTelegaLine(UpOneLineTelegramRequest $upOneLineTelegramRequest)
    {
        $this->TelegramInviteUsers->upOneLine(request('id'),request('where'),request('data'));
        return response()->json([
            'status' => 'success',
            'message' =>'Обновил значение',
        ], 200);
    }
    public function getInBaseCount(GetCountTelegramRequest $getCountTelegramRequest)
    {
        $count=$this->TelegramUsers->getCount(request('id'));
        return response()->json([
            'status' => 'success',
            'message' =>'Получил количество',
            'count' =>$count,
        ], 200);
    }
    public function addOneClientStroka(Request $request)
    {
        $this->oneClientTelegramLine->addLine(request('id'));
        return redirect()->back()->with('success', 'Операция выполнена успешно');
    }
}
