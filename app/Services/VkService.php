<?php

namespace App\Services;


use App\Models\NotReadyResults;
use App\Models\OneClientSettingsGroupsTelegramLine;

class VkService
{

    protected $oneClientSettingsGroupsTelegramLine;
    protected $notReadyResults;

    public function __construct(
        OneClientSettingsGroupsTelegramLine $oneClientSettingsGroupsTelegramLine,
        NotReadyResults $notReadyResults
    )
    {
        $this->oneClientSettingsGroupsTelegramLine = $oneClientSettingsGroupsTelegramLine;
        $this->notReadyResults = $notReadyResults;
    }

    public function deleteErrors($posts)
    {
        $errorArr=[];
        $postsArr=[];
        foreach($posts as $post)
        {
            if(isset($post->error))
            {
                $errorArr[]=$post;
            }
            else
            {
                $postsArr[]=$post;
            }
        }
        $finalArr=[$errorArr,$postsArr];
        return $finalArr;
    }
    public function reverseArr($posts)
    {
        $finalArr=[];
        foreach($posts as $post)
        {
            //тут лежат посты каждой группы $post
            $finalArr[]=array_reverse($post->response->items);
        }
        return $finalArr;
    }
    public function techFilter($groups)
    {
        $finalArr=[];

        foreach($groups as $group)
        {

            $tempArr=[];
            foreach($group as $post)
            {
                $owner_id = substr($post->owner_id, 1);
                //берём записанный пост
                $lastPost=$this->oneClientSettingsGroupsTelegramLine->getPostId($owner_id);
                //если берём первый раз и в БД ничего нет
                if($lastPost==null)
                {
                    //записываю id поста в БД
                   // $this->oneClientSettingsGroupsTelegramLine->changePostIdVK($owner_id,$post->id);
                    $tempArr[]=$post;
                }
                else
                {
                    //если пост более новый то записываю в БД и беру в работу
                    if($lastPost<$post->id)
                    {
                      //  $this->oneClientSettingsGroupsTelegramLine->changePostIdVK($owner_id,$post->id);
                        $tempArr[]=$post;
                    }
                }
            }
            $finalArr[]=$tempArr;
        }
        return $finalArr;
    }


}
