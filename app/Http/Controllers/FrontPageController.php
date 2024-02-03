<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartingData;
use File;

class FrontPageController extends Controller
{
    //
    public function index(){
        set_time_limit(3600);
        if(!file_exists('combined_model.pkl'))
        {
            $modal = MachineLearningController::testModel();
        }

        $training = StartingData::all();

        $numberPositive = StartingData::where('label','=','Positive')->count();
        $numberNegative = StartingData::where('label','=','Negative')->count();
        $numberNeutral = StartingData::where('label','=','Neutral')->count();

        $numberGameplay = StartingData::where('category','=','Gameplay')->count();
        $numberGraphics = StartingData::where('category','=','Graphics')->count();
        $numberTechnical = StartingData::where('category','=','Technical')->count();
        $numberSosial = StartingData::where('category','=','Social')->count();
        $numberOthers = StartingData::where('category','=','Others')->count();

        return view('frontpage.dashboard',
        compact('training',
        'numberPositive','numberNegative','numberNeutral',
        'numberGameplay','numberGraphics','numberTechnical','numberSosial','numberOthers'));
    }

    public function retrain(){
        set_time_limit(3600);
        if(file_exists('combined_model.pkl'))
        {
            File::delete('combined_model.pkl');
        }

        return redirect()->route('frontpage.index');
    }
}
