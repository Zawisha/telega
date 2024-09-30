<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceName extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function oneClientTelegramLine()
    {
        return $this->hasOne(OneClientTelegramLine::class, 'source_id');
    }
    public function getAll()
    {
        return SourceName::all();
    }

}
