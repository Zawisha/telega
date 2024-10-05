<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckboxSearchClientRequest;
use App\Http\Requests\SearchClientsRequest;
use App\Models\MyClient;
use App\Models\NotReadyResults;
use App\Models\OneClientSettingsFiltersTelegramLine;
use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\OneClientTelegramLine;
use App\Models\SearchFilters;
use App\Models\SearchTelegramLine;
use App\Models\SourceName;
use App\Models\TelegramPhones;
use App\Services\FilterService;
use App\Services\SearchService;
use App\Services\TelegramService;
use App\Services\VkService;
use App\Traits\SessionMaddelineTrait;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use SessionMaddelineTrait;

    protected $searchTelegramLine;
    protected $searchService;
    protected $myClient;
    protected $oneClientTelegramLine;
    protected $oneClientSettingsGroupsTelegramLine;
    protected $searchFilters;
    protected $clientSettingsFiltersTelegramLine;
    protected $telegramService;
    protected $TelegramPhones;
    protected $filterService;
    protected $notReadyResults;
    protected $filtersController;
    protected $sourceName;
    protected $VKController;
    protected $vkService;

    public function __construct(
        SearchTelegramLine $searchTelegramLine,
        SearchService $searchService,
        MyClient $myClient,
        OneClientTelegramLine $oneClientTelegramLine,
        OneClientSettingsGroupsTelegramLine $oneClientSettingsGroupsTelegramLine,
        SearchFilters $searchFilters,
        OneClientSettingsFiltersTelegramLine $clientSettingsFiltersTelegramLine,
        TelegramService $telegramService,
        TelegramPhones $TelegramPhones,
        FilterService $filterService,
        NotReadyResults $notReadyResults,
        FiltersController $filtersController,
        SourceName $sourceName,
        VKController $VKController,
        VkService $vkService
    )
    {
        $this->searchTelegramLine=$searchTelegramLine;
        $this->searchService=$searchService;
        $this->myClient=$myClient;
        $this->oneClientTelegramLine=$oneClientTelegramLine;
        $this->oneClientSettingsGroupsTelegramLine=$oneClientSettingsGroupsTelegramLine;
        $this->searchFilters=$searchFilters;
        $this->clientSettingsFiltersTelegramLine=$clientSettingsFiltersTelegramLine;
        $this->telegramService=$telegramService;
        $this->TelegramPhones=$TelegramPhones;
        $this->filterService=$filterService;
        $this->notReadyResults=$notReadyResults;
        $this->filtersController=$filtersController;
        $this->sourceName=$sourceName;
        $this->VKController=$VKController;
        $this->vkService=$vkService;

    }

    public function addStrokaSearchClientTelegram()
    {
        $newLine=$this->searchTelegramLine->addLine();
        return response()->json([
            'status' => 'success',
            'message' =>'Строка добавлена',
            'newLine' =>$newLine,
        ], 200);
    }
    public function editLineSearchSettings($id)
    {
        $oneLine=$this->searchService->getOneLine($id);
        $clients=$this->myClient->getAllClients();
        $lines=$this->oneClientTelegramLine->getAll($id);
        $filters=$this->searchFilters->getAll();
        $sourceName=$this->sourceName->getAll();
//        dd($lines);
        return view('search.editOneLine', ['oneLine' => $oneLine,'clients' => $clients,'id' => $id,'lines'=>$lines,'filters'=>$filters,'sourceName'=>$sourceName]);
    }
    public function addGroupEditLine()
    {
        $this->oneClientSettingsGroupsTelegramLine->addGroup(request('line_id'));
        return redirect()->back()->with('success', 'Операция выполнена успешно');
    }
    public function editGroupName()
    {
        $this->oneClientSettingsGroupsTelegramLine->changeNameGroup(request('group_id'),request('group_name'));
        return response()->json(['status' => 'success']);
    }
    public function deleteGroupEditLine()
    {
        $this->oneClientSettingsGroupsTelegramLine->deleteGroup(request('line_id'));
        return redirect()->back()->with('success', 'Операция выполнена успешно');
    }
    public function changeCheckboxStatus()
    {

        //если добавляем линию
        if(request('status')=='true')
        {
            $this->clientSettingsFiltersTelegramLine->addFilter(request('line_id'),request('filter_id'));
        }
        else
        {
            $this->clientSettingsFiltersTelegramLine->deleteFilter(request('line_id'),request('filter_id'));
        }
        return response()->json(['status' => 'success']);
    }
    public function updateSearchClient()
    {
        $this->searchTelegramLine->updateClientInLine(request('lineId'),request('client_id'));
        return response()->json(['status' => 'success']);
    }

    public function updateCheckboxClientList(CheckboxSearchClientRequest $checkboxSearchClientRequest)
    {
        $this->searchTelegramLine->updateVkl(request('id'),request('data'));
        return response()->json([
            'status' => 'success',
            'message' =>'Поменял чекбокс',
        ], 200);
    }

    public function searchClients(SearchClientsRequest $searchClientsRequest)
    {
        //получаю все данные. имя клиента. строки с настройками. фильтры и группы строк с настройками.
        $allDataLines=$this->searchTelegramLine->globalGetAll(request('line_id'));
        //список всех ошибок
        $errorList='';
        //перебор строк настроек
        foreach($allDataLines[0]->oneClientLine as $settingLines)
        {
            //если в строке выбран фильтр телеграм
            if($settingLines['source_id']=='1')
            {
                //пока работаю с временного телефона
                $phone='+380991106635';
                $MadelineProto=$this->madAuth($phone,'not');
                //передаю список всех групп линии в сервис для поиска и возвращаю сами группы без фильтров
                $posts=$this->telegramService->getPosts($MadelineProto,$settingLines->settingsGroups);
                //вызываю фильтры
                $posts=$this->filterService->mainFilter($settingLines,$posts);
                //сохраняю результаты
                $this->notReadyResults->storeResults($posts,$allDataLines[0]->myClient->name);
            }
            //если в строке выбран фильтр вк
            if($settingLines['source_id']=='2')
            {
              //передаю список всех групп линии в сервис для поиска и возвращаю сами группы без фильтров
              $posts=$this->VKController->getPosts($settingLines->settingsGroups);
              //отлов всех ошибок и убрал из результата
              $tempPosts=$this->vkService->deleteErrors($posts);
              $errorList=$tempPosts[0];
              $posts=$tempPosts[1];
              //4844 технофея
              //dd($errorList);
              //перевернул каждый оставшийся массив и убрал лишние поля ( количество )
              $posts=$this->vkService->reverseArr($posts);
              //оставить только новые посты и записать номер поста самого последнего
                //ВЕРНУТЬ ЗАПИСЬ ID ПОСТА
              $posts=$this->vkService->techFilter($posts);
              //вызываю фильтры СДЕЛАТЬ ПОСЛЕДОВАТЕЛЬНЫЕ ФИЛЬТРЫ
              $posts=$this->filterService->mainFilter($settingLines,$posts);

                dd($posts);


            }

        }

        return response()->json([
            'status' => 'success',
            'message' =>'Собрал группы',
            'errorList' =>$errorList,
        ], 200);
    }
    public function deleteEditLine()
    {
        $this->searchService->deleteEditLine(request('line_id'));
        return redirect()->back()->with('success', 'Операция выполнена успешно');
    }
    public function deleteClientLine()
    {
        //удаляем все зависимые строки и фильтры
       $mainLine=$this->searchTelegramLine->getOneLine(request('id'));
       foreach($mainLine[0]->oneClientLine as $oneLine)
       {
           $this->searchService->deleteEditLine($oneLine['id']);
       }
        //удаляем главную строку
        $this->searchTelegramLine->deleteOneLine(request('id'));
        return response()->json([
            'status' => 'success',
            'message' =>'Строка удалена',
        ], 200);
    }
    public function addReadyClient()
    {
        $this->searchService->processingClient(request('id'),request('choose'));
        $post=$this->notReadyResults->getOneNotReadyPost();
        return view('ready.notReady', ['post' => $post]);

    }
    public function updateSource()
    {
        $this->oneClientTelegramLine->updateSource(request('lineId'),request('sourceId'));
        return response()->json(['status' => 'success']);
    }

}
