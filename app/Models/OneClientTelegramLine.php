<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneClientTelegramLine extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function oneCLientBelLine()
    {
        return $this->belongsTo(SearchTelegramLine::class);
    }
    public function settingsGroups()
    {
        return $this->hasMany(OneClientSettingsGroupsTelegramLine::class, 'line_id');
    }
    public function settingsFilter()
    {
        return $this->hasMany(OneClientSettingsFiltersTelegramLine::class, 'line_id');
    }
    // Определяем связь с моделью SourceName
    public function sourceName()
    {
        return $this->belongsTo(SourceName::class, 'source_id');
    }

    public function addLine($id)
    {
        return OneClientTelegramLine::create(
           ['one_search_line'=>$id]
        );
    }
    public function getAll($id)
    {
       return OneClientTelegramLine::where('one_search_line',$id)
           ->with(['settingsGroups', 'settingsFilter'])
           ->get();
    }
    public function deleteLine($line_id)
    {
        OneClientTelegramLine::where('id',$line_id)->delete();
    }
    public function updateSource($id,$sourceId)
    {
        OneClientTelegramLine::where('id',$id)->
        update(
            ['source_id'=>$sourceId]
        );

    }
}
