<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\FiltersController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TelegramController;
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});


Route::get('/',[MainController::class, 'index']);
Route::get('/addTelegramUser',[TelegramController::class, 'index']);
Route::get('/inviteTelegram',[TelegramController::class, 'inviteTelegram']);
Route::get('/databaseTelegram',[TelegramController::class, 'databaseTelegram']);
Route::get('/nameGroup',[TelegramController::class, 'nameGroup']);
Route::get('/dbTelegram',[TelegramController::class, 'dbTelegram']);
Route::get('/checkClient',[CheckController::class, 'index']);
Route::get('/telegramNavi',[MainController::class, 'telegramView']);
Route::get('/searchNavi',[MainController::class, 'searchView']);
Route::get('/newClientTelegramSearch',[MainController::class, 'newClientTelegramSearch']);
Route::get('/adminSearch',[MainController::class, 'adminSearch']);
Route::get('/editLineSearchSettings/{id}',[SearchController::class, 'editLineSearchSettings']);
Route::get('/filters',[FiltersController::class, 'index']);
Route::get('/filter/{id}',[FiltersController::class, 'showOneFilter']);
Route::get('/notReadyFilter',[FiltersController::class, 'notReadyFilter']);
Route::get('/readyFilter',[FiltersController::class, 'readyFilter']);

Route::post('/saveNewTelegramUser',[TelegramController::class, 'saveNewTelegramUser']);
Route::post('/getAuthCodeTelegram',[TelegramController::class, 'getAuthCodeTelegram']);
Route::post('/sendCode',[TelegramController::class, 'sendCode']);
Route::post('/joinToGroup',[TelegramController::class, 'joinToGroup']);
Route::post('/addGroup',[TelegramController::class, 'addGroup']);
Route::post('/deleteGroup',[TelegramController::class, 'deleteGroup']);
Route::post('/addTelegramUsersToGroup',[TelegramController::class, 'addTelegramUsersToGroup']);
Route::post('/addStrokaTelegramInvite',[TelegramController::class, 'addStrokaTelegramInvite']);
Route::post('/upDataTelegaLine',[TelegramController::class, 'upDataTelegaLine']);
Route::post('/getInBaseCount',[TelegramController::class, 'getInBaseCount']);
Route::post('/deleteLine',[TelegramController::class, 'deleteLine']);
Route::post('/startInvite',[TelegramController::class, 'startInvite']);
Route::post('/checkClient',[CheckController::class, 'checkClient'])->name('checkClient');
Route::post('/addClient',[AdminController::class, 'addClient'])->name('addClient');
Route::post('/addStrokaSearchClientTelegram',[SearchController::class, 'addStrokaSearchClientTelegram'])->name('addStrokaSearchClientTelegram');
Route::post('/addOneClientStroka',[TelegramController::class, 'addOneClientStroka'])->name('addOneClientStroka');
Route::post('/addGroupEditLine',[SearchController::class, 'addGroupEditLine'])->name('addGroupEditLine');
Route::post('/editGroupName',[SearchController::class, 'editGroupName'])->name('editGroupName');
Route::post('/deleteGroupEditLine',[SearchController::class, 'deleteGroupEditLine'])->name('deleteGroupEditLine');
Route::post('/changeCheckboxStatus',[SearchController::class, 'changeCheckboxStatus'])->name('changeCheckboxStatus');
Route::post('/updateSearchClient',[SearchController::class, 'updateSearchClient'])->name('updateSearchClient');
Route::post('/updateCheckboxClientList',[SearchController::class, 'updateCheckboxClientList'])->name('updateCheckboxClientList');
Route::post('/searchClients',[SearchController::class, 'searchClients'])->name('searchClients');
Route::post('/slovoAdd',[FiltersController::class, 'slovoAdd'])->name('slovoAdd');
Route::post('/deleteEditLine',[SearchController::class, 'deleteEditLine'])->name('deleteEditLine');
Route::post('/deleteClientLine',[SearchController::class, 'deleteClientLine'])->name('deleteClientLine');
Route::post('/addReadyClient',[SearchController::class, 'addReadyClient'])->name('addReadyClient');
Route::post('/getClient',[FiltersController::class, 'getClient'])->name('getClient');


