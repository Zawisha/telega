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
        return ReadyResults::where('used',0)
            ->where('archive',0)
            ->first();
    }
    public function getOneReadyPostClient($clientName)
    {
        return ReadyResults::where('used',0)
            ->where('client_name',$clientName)
            ->where('archive',0)
            ->first();
    }
    public function updateUsed($id)
    {
        ReadyResults::where('id',$id)->
        update(
            ['used'=>true]
        );
    }
    public function updateUsedArchive($id)
    {
        ReadyResults::where('id',$id)->
        update(
            ['archive'=>true]
        );
    }
    public function getCount()
    {
        return ReadyResults::where('used',0)
            ->where('archive',0)
            ->count();
    }
    public function getCountClient($clientName)
    {
        return ReadyResults::where('used',0)
            ->where('client_name',$clientName)
            ->where('archive',0)
            ->count();
    }
    public function getClientsName()
    {
        return ReadyResults::where('used',0)
            ->distinct()
            ->pluck('client_name');;
    }
    public function getArchivePost($clientName)
    {
        return ReadyResults::where('client_name',$clientName)
            ->where('used',0)
            ->where('archive',1)
            ->first();
    }
    public function getCountClientArchive($clientName)
    {
        return ReadyResults::where('used',0)
            ->where('client_name',$clientName)
            ->where('archive',1)
            ->count();
    }
    public function getArchivePostNext($clientName,$id)
    {
        return ReadyResults::where('client_name',$clientName)
            ->where('used',0)
            ->where('archive',1)
            ->where('id','>',$id)
            ->first();
    }
}
