<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopSlovaFilters extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAll()
    {
        return StopSlovaFilters::all();
    }
    public function addSlovo($slovo)
    {
       return StopSlovaFilters::create([
            'slovo' => $slovo,
        ]);
    }

}
