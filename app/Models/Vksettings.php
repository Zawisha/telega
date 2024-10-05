<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vksettings extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getFirst()
    {
        return Vksettings::firstOrNew([], ['account_info' => '', 'token' => '']);
    }
    public function upToken($account_info,$token)
    {
        Vksettings::updateOrCreate(
            ['id' => 1], // Условие поиска (здесь ищем запись с id = 1)
            [
                'account_info' => $account_info,
                'token' => $token
            ] // Данные для обновления или создания
        );
    }
    public function getToken()
    {
        return Vksettings::first()->value('token');
    }
}
