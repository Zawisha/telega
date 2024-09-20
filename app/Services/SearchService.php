<?php

namespace App\Services;

use App\Models\NotReadyResults;
use App\Models\OneClientSettingsFiltersTelegramLine;
use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\OneClientTelegramLine;
use App\Models\ReadyResults;
use App\Models\SearchTelegramLine;

class SearchService
{
    protected $telegramInviteUsers;
    protected $oneClientTelegramLine;
    protected $oneClientSettingsGroupsTelegramLine;
    protected $oneClientSettingsFiltersTelegramLine;
    protected $notReadyResults;
    protected $readyResults;

    public function __construct(
        SearchTelegramLine $searchTelegramLine,
        OneClientTelegramLine $oneClientTelegramLine,
        OneClientSettingsGroupsTelegramLine $oneClientSettingsGroupsTelegramLine,
        OneClientSettingsFiltersTelegramLine $oneClientSettingsFiltersTelegramLine,
        NotReadyResults $notReadyResults,
        ReadyResults $readyResults
    )
    {
        $this->searchTelegramLine = $searchTelegramLine;
        $this->oneClientTelegramLine = $oneClientTelegramLine;
        $this->oneClientSettingsGroupsTelegramLine = $oneClientSettingsGroupsTelegramLine;
        $this->oneClientSettingsFiltersTelegramLine = $oneClientSettingsFiltersTelegramLine;
        $this->notReadyResults = $notReadyResults;
        $this->readyResults = $readyResults;
    }

    public function getFullLines()
    {
        return $this->searchTelegramLine->getAll();
    }
    public function getOneLine($id)
    {
       return $this->searchTelegramLine->getOneLine($id);
    }
    //метод удаления линии у клиента ( ВНИМАНИЕ: это не главная линия а линия по адресу editLineSearchSettings
    public function deleteEditLine($line_id)
    {
        //удаляем фильтры
        $this->oneClientSettingsFiltersTelegramLine->deleteLineFilters($line_id);
        //удаляем группы
        $this->oneClientSettingsGroupsTelegramLine->deleteGroups($line_id);
        //удаляем саму линию
        $this->oneClientTelegramLine->deleteLine($line_id);
    }

    public function processingClient($id,$choose)
    {
      $notReadyResult=$this->notReadyResults->getOneById($id);
        if($choose=='true')
        {
            $this->readyResults->addResult($notReadyResult[0]);
        }
        $this->notReadyResults->updateUsed($id);
    }

}
