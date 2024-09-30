<?php

namespace App\Services;



use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\SearchErrors;
use App\Models\TelegramInviteUsers;
use App\Models\TelegramUsers;
use App\Traits\SessionMaddelineTrait;

class TelegramService
{
    use SessionMaddelineTrait;
    protected $telegramInviteUsers;
    protected $telegramUsers;
    protected $oneClientSettingsGroupsTelegramLine;
    protected $searchErrors;

    public function __construct(
        TelegramInviteUsers $telegramInviteUsers,
        TelegramUsers $telegramUsers,
        OneClientSettingsGroupsTelegramLine $oneClientSettingsGroupsTelegramLine,
        SearchErrors $searchErrors
    )
    {
        $this->telegramInviteUsers = $telegramInviteUsers;
        $this->telegramUsers = $telegramUsers;
        $this->oneClientSettingsGroupsTelegramLine = $oneClientSettingsGroupsTelegramLine;
        $this->searchErrors = $searchErrors;
    }
    public function getFullLines()
    {
       return $this->telegramInviteUsers->getAllLines();
    }
    public function getUserForInvite($groupId)
    {
        //проверка сколько есть в БД людей этой группы для инвайта
       $count=$this->telegramUsers->getCount($groupId);
       if($count>0)
       {
           $user=$this->telegramUsers->getOneUser($groupId);
           $this->telegramUsers->updateUserStatus($user->id);
           return [
               'success' => true,
               'message' => 'Пользователь получен',
               'user' => $user,
           ];
       }
       else
       {
           return [
               'success' => false,
               'message' => 'Закончилась БД для инвайта',
           ];
       }
    }
    //при успешном добавлении, есть вероятность что не добавлено, вот это надо проверять
    public function processingInviteRespSuc($resp,$count)
    {
        $count+=1;
        //пользователь добавлен
        if(empty($resp['missing_invitees']))
        {

            return $count;
        }
        else
        {
            return $count;
        }
    }

    //при не успешном добавлении надо выделять критические и не критические ошибки
    public function processingInviteRespFail($message,$phone)
    {
        $crytical=false;
        $prefixes = ['FLOOD_WAIT', 'PEER_FLOOD'];
        //если строка начинается с FLOOD_WAIT то переменная критическая принимает значение true
        if (array_filter($prefixes, fn($prefix) => str_starts_with($message, $prefix))) {
            $crytical=true;
        }
        return [
            'crytical' => $crytical,
            'message' => $message,
        ];
    }
    //получаю все посты из групп
    public function getPosts($MadelineProto,$allGroups)
    {
        $workFlag=true;
        $finalMessages=[];
        //у каждой строки забираем группы по одной
        foreach($allGroups as $oneGroup)
        {
            $offset=0;
            $workFlag=true;
            $groupMessageFirstId='';
              while(($workFlag)&&($offset<300))
              {
            //если имя группы не пустое
            if(($oneGroup->group_name!=null)&&($oneGroup->group_name!=''))
            {
                try {
                        $messages_Messages = $MadelineProto->messages->getHistory
                        (
                            peer:$oneGroup->group_name,
                            add_offset:$offset,
                        );
                        //забираю последний id поста и записываю в БД
                        if (!empty($messages_Messages['messages'])) {

                            //первый случай. если это новая группа и запускается первый раз то в колонке post_id будет null
                            if($oneGroup->post_id ==null)
                            {

                                //берём id первого сообщения и записываем в БД
                                $firstMessage = $messages_Messages['messages'][0]['id'];
                                $this->oneClientSettingsGroupsTelegramLine->changePostId($oneGroup->id,$firstMessage);
                                foreach ($messages_Messages['messages'] as $oneMessage)
                                {
                                    $oneMessage['group_name']='https://t.me/'.ltrim($oneGroup->group_name, '@');
                                    $finalMessages[]=$oneMessage;
                                }
                                $workFlag=false;
                            }
                            //если не первый раз
                            else
                            {
                                //делаем перебор результатов
                                foreach ($messages_Messages['messages'] as $oneMessage)
                                {
                                    //если id сообщения меньше чем id в базе то добавляем его в итоговый массив
                                    if($oneMessage['id']>$oneGroup->post_id)
                                    {

                                        $oneMessage['group_name']='https://t.me/'.ltrim($oneGroup->group_name, '@');
                                        $finalMessages[]=$oneMessage;
                                        if($groupMessageFirstId=='')
                                        {
                                            $groupMessageFirstId=$oneMessage['id'];
                                        }
                                    }
                                    //если равно или больше то заканчиваем работу с группой и берём следующую
                                    else
                                    {
                                        if($groupMessageFirstId!=='')
                                        {
                                            $this->oneClientSettingsGroupsTelegramLine->changePostId($oneGroup->id,$groupMessageFirstId);
                                        }
                                        $workFlag=false;
                                    }
                                }
                                $offset+=20;
                            }
                        }
                    }
                        catch(\Exception $e)
                        {
                            $workFlag=false;
                            $this->searchErrors->saveError($oneGroup->line_id,$oneGroup->group_name,$e->getMessage());
                        }

            }

            }//одна группа (while)
        }//все группы (foreach)
         return ($finalMessages);
    }

}
