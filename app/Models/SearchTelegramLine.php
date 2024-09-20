<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchTelegramLine extends Model
{
    use HasFactory;
    protected $guarded = false;


    // Определение обратного отношения "один к одному" с таблицей `my_clients`
    public function myClient()
    {
        return $this->belongsTo(MyClient::class);
    }

    public function oneClientLine()
    {
        return $this->hasMany(OneClientTelegramLine::class, 'one_search_line');
    }


    public function addLine()
    {
        return SearchTelegramLine::create();
    }
    public function getAll()
    {
        $lines= SearchTelegramLine::with('myClient')->get();
        $lines = $lines->map(function ($line) {
            $line->vkl = (bool) $line->vkl; // Преобразуем 0 или 1 в boolean
            return $line;
        });
        return $lines;
    }
    public function getOneLine($id)
    {
        return  SearchTelegramLine::where('id',$id)
            ->with(['oneClientLine', 'myClient'])
            ->get();
    }
    public function updateClientInLine($id, $myClientId)
    {
        SearchTelegramLine::where('id',$id)->
        update(
            ['my_client_id'=>$myClientId]
        );
    }
    public function updateVkl($id,$data)
    {
        SearchTelegramLine::where('id',$id)->
        update(
            ['vkl'=>$data]
        );
    }

    public function globalGetAll($id)
    {
        return SearchTelegramLine::where('id',$id)
            ->with([
                'myClient',
                'oneClientLine',
                'oneClientLine.settingsGroups', // Вложенные отношения
                'oneClientLine.settingsFilter'  // Вложенные отношения
                ])
            ->get();
    }
    public function deleteOneLine($id)
    {
        SearchTelegramLine::where('id',$id)->delete();
    }
}
