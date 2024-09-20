<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyClient extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Определение отношения "один к одному" с таблицей `search_telegram_lines`
    public function searchTelegramLine()
    {
        return $this->hasOne(SearchTelegramLine::class);
    }

    public function addNewClient($name,$desc)
    {
        MyClient::create([
            'name' => $name,
            'desc' => $desc,
        ]);
    }
    public function getAllClients()
    {
        return MyClient::all();
    }

}
