<?php

namespace App\Http\Controllers;

use App\Models\SearchTelegramLine;
use App\Services\SearchService;
use Illuminate\Http\Request;

class MainController extends Controller
{

    protected $searchService;

    public function __construct(
        SearchService $searchService
    )
    {
        $this->searchService=$searchService;
    }

    public function index()
    {
        return view('main.main');
    }
    public function telegramView()
    {
        return view('main.telegram');
    }

    public function searchView()
    {
        return view('main.search');
    }
    public function newClientTelegramSearch()
    {
        $lines=$this->searchService->getFullLines();
        $transfer=[
            'lines' => $lines,
        ];
        return view('search.searchTelegram', ['transfer' => $transfer]);
    }
    public function adminSearch()
    {
        return view('search.adminSearch');
    }

}
