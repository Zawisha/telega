<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchErrors extends Model
{
    use HasFactory;
    protected $guarded = false;

        public function saveError($line_id,$group_name,$message)
        {
            SearchErrors::create(
                [
                    'line_id'=>$line_id,
                    'group_name'=>$group_name,
                    'message'=>$message,
                ]);
        }

}
