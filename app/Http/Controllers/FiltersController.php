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
    public function notReadyFilter()
    {
        $post=$this->notReadyResults->getOneNotReadyPost();
        return view('ready.notReady', ['post' => $post]);
    }
    public function readyFilter()
    {
        $post=$this->readyResults->getOneReadyPost();
        $countClients=$this->readyResults->getCount();
        return view('ready.ready', ['post' => $post,'countClients'=>$countClients]);
    }
    public function getClient()
    {
        $this->readyResults->updateUsed(request('id'));
        return $this->readyFilter();
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
}
