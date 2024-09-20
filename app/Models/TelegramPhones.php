<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class TelegramPhones extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Обратная связь один к одному с TelegramInviteUsers
    public function inviteUser()
    {
        return $this->hasOne(TelegramInviteUsers::class, 'phone_id');
    }

    public function createPhone($requestVal)
    {
        TelegramPhones::create([
            'phone' => $requestVal['new_user_telephone'],
            'api_id' => $requestVal['api_id'],
            'api_hash' => $requestVal['api_hash']
        ]);
    }
    public function getApiIDHash($phone)
    {
        return TelegramPhones::where('phone',$phone)->first();
    }
    public function getAllPhones()
    {
        return TelegramPhones::all();
    }
    public function getPhoneById($id)
    {
       return TelegramPhones::where('id', $id)->pluck('phone')->first();
    }
}
