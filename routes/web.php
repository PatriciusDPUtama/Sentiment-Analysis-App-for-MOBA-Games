<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dota2Controller;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\SmiteController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\WildRiftController;
use App\Http\Controllers\MobileLegendsController;
use App\Http\Controllers\CrawlerController;
use App\Http\Controllers\MachineLearningController;
use App\Http\Controllers\FrontPageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[FrontPageController::class,'index'])->name('frontpage.index');
Route::get('/retrain',[FrontPageController::class,'retrain'])->name('retrain');

Route::get('/dota2',[Dota2Controller::class,'index'])->name('dota2.index');
Route::post('/dota2/save',[Dota2Controller::class,'saveToTraining'])->name('dota2.saveToTraining');
Route::post('/dota2/review',[Dota2Controller::class,'getreview'])->name('dota2.getreview');

Route::get('/smite',[SmiteController::class,'index'])->name('smite.index');
Route::post('/smite/save',[SmiteController::class,'saveToTraining'])->name('smite.saveToTraining');
Route::post('/smite/review',[SmiteController::class,'getreview'])->name('smite.getreview');

Route::get('/mlbb',[MobileLegendsController::class,'index'])->name('mlbb.index');
Route::post('/mlbb/save',[MobileLegendsController::class,'saveToTraining'])->name('mlbb.saveToTraining');
Route::post('/mlbb/review',[MobileLegendsController::class,'getreview'])->name('mlbb.getreview');

Route::get('/pokemon',[PokemonController::class,'index'])->name('poke.index');
Route::post('/pokemon/save',[PokemonController::class,'saveToTraining'])->name('poke.saveToTraining');
Route::post('/pokemon/review',[PokemonController::class,'getreview'])->name('poke.getreview');

Route::get('/wildrift',[WildRiftController::class,'index'])->name('wr.index');
Route::post('/wildrift/save',[WildRiftController::class,'saveToTraining'])->name('wr.saveToTraining');
Route::post('/wildrift/review',[WildRiftController::class,'getreview'])->name('wr.getreview');

Route::get('/scrape',[CrawlerController::class,'test'])->name('test');
Route::get('/gplay',[CrawlerController::class,'getGoogleplay'])->name('getGoogleplay');
Route::get('/crawl',[CrawlerController::class,'crawl'])->name('crawl');

Route::get('/testing',[MachineLearningController::class,'testing'])->name('testing');
Route::post('/testing/testreview',[MachineLearningController::class,'testReview'])->name('test.testReview');

Route::get('/testmodel',[MachineLearningController::class,'testModel'])->name('testModel');

Route::post('/classify',[MachineLearningController::class,'classifyReview'])->name('model.classifyReview');
Route::post('/showresult',[MachineLearningController::class,'showResult'])->name('model.showresult');



