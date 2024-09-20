<?php

namespace App\Traits;


use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use App\Models\TelegramPhones;
use danog\MadelineProto\Settings\Connection;
use Illuminate\Support\Facades\File;

trait SessionMaddelineTrait
{

    protected $TelegramPhones;

    public function __construct(TelegramPhones $TelegramPhones)
    {
        $this->TelegramPhones=$TelegramPhones;
    }

    public function madAuth($phone,$newOrNot)
    {
        $apiIDApiHash=$this->TelegramPhones->getApiIDHash($phone);
        // Путь к файлу сессии
        $path=public_path().'/my_mad_sessions/'.$phone;
        // Проверяем, существует ли файл
        // Проверяем, существует ли директория
        if($newOrNot=='new')
        {
            if (File::isDirectory($path)) {
                // Удаляем директорию и всё её содержимое
                File::deleteDirectory($path);
            }
            mkdir($path);
        }

        // Создаем объект настроек приложения
        $appInfo = (new AppInfo())
            ->setApiId($apiIDApiHash->api_id)
            ->setApiHash($apiIDApiHash->api_hash);

        // Устанавливаем глобальные настройки
        $settings = (new Settings())
            ->setAppInfo($appInfo);

        $MadelineProto = new API($path,$settings);

        return $MadelineProto;
    }
}
