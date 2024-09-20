<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUsers extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Отношение с TelegramGroups
    public function group()
    {
        return $this->belongsTo(TelegramGroups::class, 'group_id');
    }

    public function addUserToDB($user,$group_id)
    {
         TelegramUsers::firstOrCreate(
            ['user_name' => $user], // Условия поиска
            ['user_name' => $user,'group_id'=>$group_id] // Значения для создания, если не найдена
        );
    }
    public function getCount($id)
    {
        return TelegramUsers::where('group_id', $id)->count();
    }
    public function getOneUser($id)
    {
        return TelegramUsers::where('group_id', $id)->where('send',false)->first();
    }
    public function updateUserStatus($id)
    {
        TelegramUsers::where('id', $id)
            ->update([
                'send' => true
            ]);
    }

}
