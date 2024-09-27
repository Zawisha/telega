<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReadyResults extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function addResult($post)
    {
        try {
            ReadyResults::create([
                'group_name' => $post['group_name'],
                'message' => $post['message'],
                'link' => $post['link'],
                'client_name' => $post['client_name'],
            ]);
        }
        catch(\Exception $e) {

        }
    }

    public function getOneReadyPost()
    {
        return ReadyResults::where('used',0)->first();
    }
    public function updateUsed($id)
    {
        ReadyResults::where('id',$id)->
        update(
            ['used'=>true]
        );
    }
    public function getCount()
    {
        return ReadyResults::where('used',0)->count();
    }

}
