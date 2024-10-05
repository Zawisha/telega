<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneClientSettingsGroupsTelegramLine extends Model
{
    use HasFactory;

    protected $guarded = false;
    // Определяем обратное отношение к главной модели
    public function searchTelegramLine()
    {
        return $this->belongsTo(OneClientSettingsFiltersTelegramLine::class);
    }
    public function addGroup($line_id)
    {
        return OneClientSettingsGroupsTelegramLine::create(
            ['line_id'=>$line_id]
        );
    }
    public function changeNameGroup($id,$name)
    {
        OneClientSettingsGroupsTelegramLine::where('id',$id)->
            update(
                ['group_name'=>$name]
        );
    }
    public function deleteGroup($line_id)
    {
        OneClientSettingsGroupsTelegramLine::where('id',$line_id)
        ->delete();
    }
    public function changePostId($id,$post_id)
    {
        OneClientSettingsGroupsTelegramLine::where('id',$id)->
        update(
            ['post_id'=>$post_id]
        );
    }
    public function deleteGroups($line_id)
    {
        OneClientSettingsGroupsTelegramLine::where('line_id',$line_id)->delete();
    }
    public function getPostId($group_name)
    {
        return OneClientSettingsGroupsTelegramLine::where('group_name',$group_name)->value('post_id');
    }
    public function changePostIdVK($group_name,$post_id)
    {
        OneClientSettingsGroupsTelegramLine::where('group_name',$group_name)->
        update(
            ['post_id'=>$post_id]
        );
    }
}
