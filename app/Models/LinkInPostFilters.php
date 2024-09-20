<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkInPostFilters extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAll()
    {
        return LinkInPostFilters::all();
    }
    public function checkLink($link_in_post)
    {
       return  LinkInPostFilters::firstOrCreate(
            ['link_in_post' => $link_in_post], // Условие для поиска записи
            ['link_in_post' => $link_in_post] // Данные для создания, если запись не найдена
        );
    }

}
