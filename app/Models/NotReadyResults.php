<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotReadyResults extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function storeResults($posts,$clientName)
    {
        foreach($posts as $post)
        {
            NotReadyResults::create([
                'group_name' => $post['group_name'],
                'message' => $post['message'],
                'link' => $post['group_name'].'/'.$post['id'],
                'client_name' => $clientName,
            ]);
        }
    }
    public function getOneNotReadyPost()
    {
        return NotReadyResults::where('used',0)->first();
    }
    public function getOneById($id)
    {
        return NotReadyResults::where('id',$id)->get();
    }
    public function updateUsed($id)
    {
        NotReadyResults::where('id',$id)->
        update(
            ['used'=>true]
        );
    }
}
