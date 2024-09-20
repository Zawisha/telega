<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VseAvtoryChataFilters extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAllOldAvtors()
    {
       return VseAvtoryChataFilters::pluck('avtor_id')->toArray();
    }
    public function addAvtor($avtor_id)
    {
        VseAvtoryChataFilters::create([
            'avtor_id' => $avtor_id,
        ]);
    }

}
