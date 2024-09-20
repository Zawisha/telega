<?php

namespace App\Services;

use App\Models\LinkInPostFilters;
use App\Models\SearchTelegramLine;
use App\Models\StopSlovaFilters;
use App\Models\VseAvtoryChataFilters;

class FilterService
{
    protected $vseAvtoryChataFilters;
    protected $stopSlovaFilters;
    protected $linkInPostFilters;


    public function __construct(
        VseAvtoryChataFilters $vseAvtoryChataFilters,
        StopSlovaFilters $stopSlovaFilters,
        LinkInPostFilters $linkInPostFilters
    )
    {
        $this->vseAvtoryChataFilters = $vseAvtoryChataFilters;
        $this->stopSlovaFilters = $stopSlovaFilters;
        $this->linkInPostFilters = $linkInPostFilters;
    }

    public function mainFilter($settingLines,$posts)
    {
        $posts=$this->techFilter($posts);
        if($settingLines->settingsFilter->contains('filter_id',1))
        {
            $posts=$this->vseAvtoryChataFilter($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',2))
        {
            $posts=$this->stopSlova($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',3))
        {
            $posts=$this->linkInPostFilter($posts);
        }
        return $posts;
    }
    //технический фильтр убирающий знаки переноса и приводящий в нижний регистр
    public function techFilter($posts)
    {
        $newPosts=[];
        //убираем знаки переноса и приводим в нижний регистр, убираем пустые сообщения
        foreach($posts as $index=>$post)
        {
            if($post['message']!='')
            {
                $text = str_replace(["\\n", "\\p"], " ", mb_strtolower($post['message']));
                $text = str_replace("\n", " ", $text);
                $posts[$index]['message']=$text;
                $newPosts[]=$posts[$index];
            }
        }
        return $newPosts;
    }
    //первый фильтр убирающий дубли авторов
    public function vseAvtoryChataFilter($posts)
    {
        $existingAuthors = $this->vseAvtoryChataFilters->getAllOldAvtors();
        $newPosts=[];
        foreach($posts as $post)
        {
            if(!in_array($post['from_id'], $existingAuthors))
            {
                $newPosts[]=$post;
                $this->vseAvtoryChataFilters->addAvtor($post['from_id']);
            }
        }
        return $newPosts;
    }
    //второй фильтр убирающий посты со стоп словами
    public function stopSlova($posts)
    {
        $stopSlova=$this->stopSlovaFilters->getAll();
        $newPosts=[];
        foreach($posts as $post)
        {
            //всё в нижний регистр
            $postMessage=mb_strtolower($post['message']);
            $flag=false;
            foreach ($stopSlova as $stopSlovo)
            {
                if(mb_strpos($postMessage,$stopSlovo->slovo)!== false)
                {
                    $flag=true;
                    break;
                }
            }
            if($flag==false)
            {
                $newPosts[]=$post;
            }
        }
        return $newPosts;
    }
    public function linkInPostFilter($posts)
    {
        $newPosts=[];
        foreach($posts as $post)
        {
            $flag=false;
            //смотрим есть ли ссылки какого либо вида в посте (Entities)
            if(isset($post['entities']))
            {
                foreach($post['entities'] as $entity)
                {
                    if (isset($entity['url'])) {
                        // Получаем и выводим URL
                        $url = $entity['url'];
                        $tempRes=$this->linkInPostFilters->checkLink($url);
                        if($tempRes->wasRecentlyCreated)
                        {
                            $flag=true;
                        }
                    }

                }
            }
            //иногда в тексте есть а в прикреплённых нету, тоже надо вытаскивать
            if (isset($post['message'])) {
            //всё в нижний регистр
            $postMessage=mb_strtolower($post['message']);
            //определяем есть ли ссылка в посте и достаём все ссылки оттуда
            $pattern = '/https?:\/\/[^\s]+/';
            if (preg_match_all($pattern, $postMessage, $matches)) {
                //если ссылки найдены
                $urls = $matches[0]; // Массив всех найденных ссылок
                //проверяем есть ли такая ссылка и если нету то добавляем
                foreach ($urls as $oneLink) {
                    //если запись создалась (т.е. ссылки ещё нету ) вернёт true
                    $tempRes = $this->linkInPostFilters->checkLink($oneLink);
                    if ($tempRes->wasRecentlyCreated) {
                        $flag = true;
                    }
                }
                }
            }
            if($flag)
            {
                $newPosts[]=$post;
            }
        }

        return $newPosts;
    }
}
//$flag=false;
////всё в нижний регистр
//$postMessage=mb_strtolower($post['message']);
////определяем есть ли ссылка в посте и достаём все ссылки оттуда
//$pattern = '/https?:\/\/[^\s]+/';
//if (preg_match_all($pattern, $postMessage, $matches)) {
//    //если ссылки найдены
//    $urls = $matches[0]; // Массив всех найденных ссылок
//    //проверяем есть ли такая ссылка и если нету то добавляем
//    foreach($urls as $oneLink)
//    {
//        //если запись создалась (т.е. ссылки ещё нету ) вернёт true
//        $tempRes=$this->linkInPostFilters->checkLink($oneLink);
//        if($tempRes)
//        {
//            $flag=true;
//        }
//    }
//    //если найдена хоть одна новая ссылка
//    if($flag)
//    {
//        $newPosts[]=$post;
//    }
//
//}
