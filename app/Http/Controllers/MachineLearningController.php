<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;
use GuzzleHttp\Client;
use HTMLDomParser\DomFactory;
use HTMLDomParser\Dom;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MachineLearningController extends Controller
{
    //
    public function testing() {
        return view('testing.testing');
    }
    public function testReview(){
        set_time_limit(3600);
        $testText = $_POST['testText'];

        $process = new Process(['python', 'python/predict_label.py',$testText],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results = $process->getOutput();

        $process2 = new Process(['python', 'python/predict_category.py',$testText],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process2->run();

        if (!$process2->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results2 = $process2->getOutput();
        $output = "<h6>The review you entered is <strong>".$results." </strong> with a category of <strong>".$results2."</strong></h6>";

        return response()->json(array(
            'status'=>'oke',
            'msg'=>$output
        ),200);

    }

    public function classifyReview($text){
        set_time_limit(3600);
        $testText = $text;
        
        $process = new Process(['python', 'python/predict_label.py',$testText],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results = $process->getOutput();

        $process2 = new Process(['python', 'python/predict_category.py',$testText],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process2->run();

        if (!$process2->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results2 = $process2->getOutput();
        $output = array(
            'label' => $results,
            'category' => $results2,
        );

        return $output;
        // return $results;
    }

    public function testModel(){

        $process = new Process(['python', 'python/model.py'],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results = json_decode($process->getOutput());
        return $results;
    }


    public function testDB(){

        $process = new Process(['python', 'python/database.py'],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        dd($process->getOutput());
        // $results = json_decode($process->getOutput());
        // return $results;
    }

    public function preprocessText($text){

        $process = new Process(['python', 'python/preprocess.py',$text],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $results = $process->getOutput();
        return $results;
    }

}
