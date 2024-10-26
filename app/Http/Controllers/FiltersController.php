<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSlovoRequest;
use App\Http\Requests\AddSlovoVKRequest;
use App\Models\NotReadyResults;
use App\Models\ReadyResults;
use App\Models\SearchFilters;
use App\Models\StoplinksVK;
use App\Models\StopSlovaFilters;
use Illuminate\Http\Request;

class FiltersController extends Controller
{

    protected $searchFilters;
    protected $stopSlovaFilters;
    protected $notReadyResults;
    protected $readyResults;
    protected $stoplinksVK;


    public function __construct(
        SearchFilters $searchFilters,
        StopSlovaFilters $stopSlovaFilters,
        NotReadyResults $notReadyResults,
        ReadyResults $readyResults,
        StoplinksVK $stoplinksVK
    )
    {
        $this->searchFilters=$searchFilters;
        $this->stopSlovaFilters=$stopSlovaFilters;
        $this->notReadyResults=$notReadyResults;
        $this->readyResults=$readyResults;
        $this->stoplinksVK=$stoplinksVK;

    }

    public function index()
    {
        $filters=$this->searchFilters->getAll();
        $transfer=[
            'filters' => $filters,
        ];
        return view('search.filters', ['transfer' => $transfer]);
    }
    public function notReadyFilter($clientName = null)
    {
        if($clientName)
        {
            $post=$this->notReadyResults->getOneNotReadyPostClient($clientName);
        }
        else
        {
            $post=$this->notReadyResults->getOneNotReadyPost();
        }
        return view('ready.notReady', ['post' => $post]);
    }
    public function readyFilter($clientName = null, $archive=null)
    {
        //если есть имя клиента и вызван не архив
        if($clientName)
        {
            //если выбираем из архива
            if($archive=='archive')
            {
                $post=$this->readyResults->getArchivePost($clientName);
                $countClients=$this->readyResults->getCountClientArchive($clientName);
            }
            //если выбираем просто по имени
            else
            {
                $post=$this->readyResults->getOneReadyPostClient($clientName);
                $countClients=$this->readyResults->getCountClient($clientName);
            }

        }
        //общий вызов всех клиентов
        else
        {
            $post=$this->readyResults->getOneReadyPost();
            $countClients=$this->readyResults->getCount();
        }
        return view('ready.ready', ['post' => $post,'countClients'=>$countClients,'archive'=>$archive]);
    }
    public function skipArchive()
    {
        $clientName=request('clientName');
        $archive='archive';
        $post=$this->readyResults->getArchivePostNext($clientName,request('id'));
        $countClients=$this->readyResults->getCountClientArchive($clientName);
        return view('ready.ready', ['post' => $post,'countClients'=>$countClients,'archive'=>$archive]);
    }

    public function getClient()
    {
        if(request('choose')=='getIt')
        {
            $this->readyResults->updateUsed(request('id'));
        }
        else
        {
            $this->readyResults->updateUsedArchive(request('id'));
        }
        return $this->readyFilter(request('clientName'),request('archive'));
    }
    public function showOneFilter()
    {
        //все фильтры строго привязаны к своим id, даже в отображении
        if(request('id')=='1')
        {
            $filters=$this->searchFilters->getAll();
            $transfer=[
                'filters' => $filters,
            ];
            return view('search.oneFilter', ['transfer' => $transfer,'id'=>request('id')]);
        }
        if(request('id')=='2')
        {
            $slova=$this->stopSlovaFilters->getAll();
            $transfer=[
                'slova' => $slova,
            ];
            return view('search.oneFilter', ['transfer' => $transfer,'id'=>request('id')]);
        }
        if(request('id')=='3')
        {
            $slova=$this->stopSlovaFilters->getAll();
            $transfer=[
                'slova' => $slova,
            ];
            return view('search.oneFilter', ['transfer' => $transfer,'id'=>request('id')]);
        }
        if(request('id')=='4')
        {
            $slova=$this->stoplinksVK->getAll();
            $transfer=[
                'slova' => $slova,
            ];
            return view('search.oneFilter', ['transfer' => $transfer,'id'=>request('id')]);
        }
    }
    public function slovoAdd(AddSlovoRequest $addSlovoRequest)
    {
        $this->stopSlovaFilters->addSlovo(request('slovo'));
        return response()->json([
            'status' => 'success',
            'message' =>'Слово добавлено',
        ], 200);
    }

    public function slovoAddVK(AddSlovoVKRequest $addSlovoVKRequest)
    {
        $this->stoplinksVK->addSlovo(request('slovo'));
        return response()->json([
            'status' => 'success',
            'message' =>'Слово добавлено',
        ], 200);
    }
    public function notReadyFilterCommon()
    {
        //получаю имена всех клиентов где есть не обработанные данные
        $clientsName=$this->notReadyResults->getClientsName();
        return view('ready.notReadyCommon', ['clientsName' => $clientsName]);
    }
    public function ReadyFilterCommon()
    {
        //получаю имена всех клиентов где есть не обработанные данные
        $clientsName=$this->readyResults->getClientsName();
        return view('ready.ReadyCommon', ['clientsName' => $clientsName]);
    }
}
