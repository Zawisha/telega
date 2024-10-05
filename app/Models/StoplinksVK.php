<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoplinksVK extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAll()
    {
        return StoplinksVK::all();
    }
    public function addSlovo($slovo)
    {
        return StoplinksVK::create([
            'slovo' => $slovo,
        ]);
    }
    public function checkLink($postMessage)
    {
       $links=StoplinksVK::all();
       foreach($links as $link)
       {
           //ищем есть ли такая ссылка
           if (strpos($postMessage, $link->slovo) !== false) {
               // Подстрока найдена
               return false;
           }
       }
        return true;
    }

}
