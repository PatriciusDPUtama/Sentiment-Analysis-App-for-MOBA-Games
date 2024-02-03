<?php

namespace App\Http\Controllers;

use URL;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use HTMLDomParser\DomFactory;
use HTMLDomParser\Dom;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CrawlerController extends Controller
{
    //
    public function scrape($gameid,$keyword){
        $reviews = array();
        $nameReview = array();
        $limit = 3;

        $tr = new GoogleTranslate('en'); 
        $client = new Client();

        $response = $client->request('GET', "https://steamcommunity.com/app/".$gameid."/discussions/search/?q=".$keyword."&sort=time&p=1");
        $html = $response->getBody()->getContents();
        $dom = DomFactory::load($html);
        
        $elements = $dom->find('div[class="forum_searchresult_reply_inner"]');
        $names = $dom->find('div[class="searchresult_author"]');
        
        foreach ($elements as $key=>$element) {
            $rev = $tr->translate($element->text());
            array_push($reviews,$rev);
            if($key+1==$limit)
            {
                break;
            }
        }

        foreach ($names as $key=>$name) {
            array_push($nameReview,$name->text());
            if($key+1==$limit)
            {
                break;
            }
        }

        return array($reviews,$nameReview);  
    }

    public function getGoogleplay($packagename,int $number){

        $process = new Process(['python', 'python/googleplay.py',$packagename,$number],null,
        ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        // dd($process->getOutput());
        $results = json_decode($process->getOutput());
        return $results;
    }

    public function crawl(){
        $reviews = array();
        $nameReview = array();
        $limit = 10;
        
        $tr = new GoogleTranslate('en'); 
        $client = new Client();

        $response = $client->request('GET', "https://steamcommunity.com/app/570/discussions/search/?sort=time&q=cool");
        $html = $response->getBody()->getContents();
        $dom = DomFactory::load($html);
        
        $elements = $dom->find('div[class="forum_searchresult_reply_inner"]');
        $names = $dom->find('div[class="searchresult_author"]');
        
        foreach ($elements as $key=>$element) {
            $rev = $tr->translate($element->text());
            array_push($reviews,$rev);
            if($key+1==$limit)
            {
                break;
            }
        }

        foreach ($names as $key=>$name) {
            array_push($nameReview,$name->text());
            if($key+1==$limit)
            {
                break;
            }
        }
        
        foreach($reviews as $rev)
        {
            echo $rev."<br><br>";
        }
        echo "<br>";

        foreach($nameReview as $nm)
        {
            echo $nm."<br><br>";
        }
    }
}
