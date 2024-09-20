<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneClientSettingsFiltersTelegramLine extends Model
{
    use HasFactory;
    protected $guarded = false;

    // Определяем обратное отношение к главной модели
    public function searchTelegramLine()
    {
        return $this->belongsTo(OneClientTelegramLine::class);
    }
    public function addFilter($line_id, $filter_id)
    {
        return OneClientSettingsFiltersTelegramLine::create(
            [
                'line_id'=>$line_id,
                'filter_id'=>$filter_id,
            ]
        );
    }
    public function deleteFilter($line_id, $filter_id)
    {
        return OneClientSettingsFiltersTelegramLine::where('line_id',$line_id)->where( 'filter_id',$filter_id,)->delete();
    }
    public function deleteLineFilters($line_id)
    {
        OneClientSettingsFiltersTelegramLine::where('line_id',$line_id)->delete();
    }

}
