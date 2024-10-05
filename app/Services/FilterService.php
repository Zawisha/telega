<?php

namespace App\Services;

use App\Models\LinkInPostFilters;
use App\Models\SearchTelegramLine;
use App\Models\StoplinksVK;
use App\Models\StopSlovaFilters;
use App\Models\VseAvtoryChataFilters;

class FilterService
{
    protected $vseAvtoryChataFilters;
    protected $stopSlovaFilters;
    protected $linkInPostFilters;
    protected $stoplinksVK;


    public function __construct(
        VseAvtoryChataFilters $vseAvtoryChataFilters,
        StopSlovaFilters $stopSlovaFilters,
        LinkInPostFilters $linkInPostFilters,
        StoplinksVK $stoplinksVK
    )
    {
        $this->vseAvtoryChataFilters = $vseAvtoryChataFilters;
        $this->stopSlovaFilters = $stopSlovaFilters;
        $this->linkInPostFilters = $linkInPostFilters;
        $this->stoplinksVK = $stoplinksVK;
    }

    public function mainFilter($settingLines,$posts)
    {
        //фильтры телеграм
        if($settingLines->settingsFilter->contains('filter_id',1))
        {
            $posts=$this->techFilter($posts);
            $posts=$this->vseAvtoryChataFilter($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',2))
        {
            $posts=$this->techFilter($posts);
            $posts=$this->stopSlova($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',3))
        {
            $posts=$this->techFilter($posts);
            $posts=$this->linkInPostFilter($posts);
        }
        //фильтры вк СДЕЛАТЬ НЕ ПОСЛЕДОВАТЕЛЬНО А ПАРАЛЛЕЛЬНО
        if($settingLines->settingsFilter->contains('filter_id',4))
        {
            $posts=$this->techFilterVK($posts);
            $posts=$this->linksFilterVK($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',5))
        {
            $posts=$this->reklamaVK($posts);
        }
        if($settingLines->settingsFilter->contains('filter_id',6))
        {
            $posts=$this->repostVK($posts);
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
    //технический фильтр убирающий знаки переноса и приводящий в нижний регистр
    public function techFilterVK($groups)
    {
        $newPosts=[];
        foreach ($groups as $posts)
        {
            $temp_posts=[];
            //убираем знаки переноса и приводим в нижний регистр, убираем пустые сообщения
            foreach($posts as $index=>$post)
            {
                    $text = str_replace(["\\n", "\\p"], " ", mb_strtolower($post->text));
                    $text = str_replace("\n", " ", $text);
                    $post->text=$text;
                    $temp_posts[]=$post;
            }
            $newPosts[]=$temp_posts;
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

    public function linksFilterVK($groups)
    {

        $finalArr=[];
        foreach($groups as $group)
        {
            $tempArr=[];
            foreach($group as $post)
            {
                $flag=false;
                //всё в нижний регистр
                $postMessage=mb_strtolower($post->text);
                //первый вариант когда в тексте есть https
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
                //второй вариант когда есть доменное окончание например .ru
                $pattern = '/\b[\w\.-]+\.(ru|com|net|org|edu|gov|mil|info|biz|io|co|us|de|jp|uk|fr|au|ca|cn|it|es|nl|ch|se|no|in|br|mx|pl|za|dk|fi|be|at|tr|hk|sg|kr|nz|pt|cz|il|gr|ro|sk|hu|ie|vn|th|my|tw|ua|ar|cl|pe|ph|eg|pk|sa|ae|bg|si|lt|lv|ee|lu|by|ge|md|kz|am|kg|uz|az|bd|lk|np|ba|me|mk|rs|mt|hr|is|jo|qa|kw|om|bh|lb|ma|dz|tn|ng|gh|ke|tz|et|tm|tj|al|ad|li|sm|va|mc|rs|xk|bt|mn|kh|mm|la|kr|kp|id|bn|ir|sy|iq)\b/';
                if (preg_match_all($pattern, $postMessage, $matches)) {
                    //если ссылки найдены
                    //сделать стоп ссылки, чтобы не было одинаковых
                    $flag=$this->stoplinksVK->checkLink($postMessage);
                }
                //третий вариант внутренняя ссылка на ПАБЛИК
                //ищем есть ли такая ссылка
                if (strpos($postMessage, 'club') !== false) {
                    // Подстрока найдена
                    $flag=$this->stoplinksVK->checkLink($postMessage);
                }

                if($flag)
                {
                    $tempArr[]=$post;
                }
            }
            $finalArr[]=$tempArr;
        }
        return $finalArr;
    }
    public function reklamaVK($posts)
    {
        return $posts;
    }
    //фильтр забирающий репосты
    public function repostVK($posts)
    {
        return $posts;
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
