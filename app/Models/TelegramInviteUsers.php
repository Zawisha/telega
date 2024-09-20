<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramInviteUsers extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Связь один к одному с TelegramGroups
    public function group()
    {
        return $this->belongsTo(TelegramGroups::class, 'group_id');
    }
    // Связь один к одному с TelegramPhones
    public function phone()
    {
        return $this->belongsTo(TelegramPhones::class, 'phone_id');
    }
    public function addLine()
    {
        return TelegramInviteUsers::create();
    }
    public function getAllLines()
    {
        $inviteUsers= TelegramInviteUsers::with(['group' => function($query) {
            $query->withCount(['users as countUsers' => function ($query) {
                $query->where('send', false);
            }]);
        }])->get();
        $inviteUsers->each(function ($inviteUser) {
            $inviteUser->vkl = $inviteUser->vkl == 1; // Преобразует 1 в true, 0 в false
        });
        return $inviteUsers;
    }
    public function upOneLine($id,$where,$data)
    {
        TelegramInviteUsers::where('id', $id)
            ->update([
                $where => $data
            ]);
    }
    public function deleteOneLine($id)
    {
        TelegramInviteUsers::where('id', $id)->delete();
    }

}
