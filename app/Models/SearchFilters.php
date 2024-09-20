<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchFilters extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAll()
    {
        return SearchFilters::all();
    }

}
