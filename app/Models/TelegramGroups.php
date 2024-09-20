<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramGroups extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Обратная связь один к одному с TelegramInviteUsers
    public function inviteUser()
    {
        return $this->hasOne(TelegramInviteUsers::class, 'group_id');
    }

    // Отношение с TelegramUsers
    public function users()
    {
        return $this->hasMany(TelegramUsers::class, 'group_id');
    }

    public function getAll()
    {
        return TelegramGroups::all();
    }
    public function saveNewGroup($group)
    {
     return TelegramGroups::create([
            'group_name' => $group,
        ]);
    }
    public function deleteGroup($id)
    {
        TelegramGroups::where('id',$id)->delete();
    }
    public function getGroupById($id)
    {
        return TelegramGroups::where('id', $id)->pluck('group_name')->first();
    }
}
