<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepostOwnersVK extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function addUserToDB($owner_id)
    {
       return RepostOwnersVK::firstOrCreate(
            ['owner_id' => $owner_id], // Условия поиска
            ['owner_id' => $owner_id] // Значения для создания, если не найдена
        );
    }

}
