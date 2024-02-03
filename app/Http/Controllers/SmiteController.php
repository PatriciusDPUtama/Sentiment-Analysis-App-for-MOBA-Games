<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartingData;

class SmiteController extends Controller
{
    //
    public function index(){
        return view('games.smite');
    }

    public function saveToTraining(Request $request){
        $input = $request->except(['_token']);
        $length = count($input)/4;

        for ($i=0; $i < $length; $i++) { 
            $review = $request->input("review".$i);
            $review = preg_replace('/[^A-Za-z0-9 ]/','', $review);

            if($review == "")
            {
                continue;
            }
            
            $prep = MachineLearningController::preprocessText($review);
            $sentiment = $request->input("sentiment".$i);
            $categories = $request->input("categories".$i);

            $name = $request->input("name".$i);
            $name = preg_replace('/[^A-Za-z0-9 ]/','', $name);

            if($name == "")
            {
                $name = "Blank";
            }

            $data = [
                'username'=> $name,
                'content' => $review,
                'preprocessed' => $prep,
                'label'=>$sentiment,
                'category'=>$categories
            ];

            StartingData::insert($data);
        }

        return redirect()->route('frontpage.index');
        
    }
    
    public function getreview(){
        set_time_limit(3600);
        $keyword = $_POST['keyword_review'];
        $gameid = 386360;
        $reviews = array();
        $result = CrawlerController::scrape($gameid,$keyword);

        $reviews = $result[0];
        $names = $result[1];
        
        $table = "";
        
        foreach($reviews as $key=>$review){
            $review = preg_replace('/[^A-Za-z0-9 ]/','', $review);

            $classificationResult = MachineLearningController::classifyReview($review);

            $label = trim($classificationResult['label']);
            $category = trim($classificationResult['category']);

            $review = str_replace("'","",$review);
            $name = str_replace("'","",$names[$key]);

            if($label=="Negative")
            {
                if($category=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative' selected>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay' selected>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative' selected>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics' selected>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative' selected>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical' selected>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative' selected>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social' selected>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                else 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative' selected>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others' selected>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
            }
            elseif ($label=="Positive") 
            {
                if($category=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive' selected>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay' selected>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive' selected>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics' selected>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive' selected>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical' selected>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive' selected>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social' selected>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                else 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive' selected>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral'>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others' selected>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
            }
            else
            {
                if($category=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral' selected>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay' selected>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral' selected>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics' selected>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral' selected>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical' selected>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                elseif ($category=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral' selected>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social' selected>Social</option>
                            <option value='Others'>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
                else 
                {
                    $table = $table."
                    <tr>
                    <td>".$names[$key]."</td>
                    <input type='hidden' name='review".$key."' value='".$review."'>
                    <td id='text".$key."'>".$review."</td>
                    <td id='label".$key."'>
                        <select class='form-select' name='sentiment".$key."' id='sentiment".$key."'>
                            <option value='Positive'>Positive</option>
                            <option value='Negative'>Negative</option>
                            <option value='Neutral' selected>Neutral</option>
                        </select>
                    </td>
                    <td id='category".$key."'>
                        <select class='form-select' name='categories".$key."' id='categories".$key."'>
                            <option value='Gameplay'>Gameplay</option>
                            <option value='Graphics'>Graphics</option>
                            <option value='Technical'>Technical</option>
                            <option value='Social'>Social</option>
                            <option value='Others' selected>Others</option>
                        </select>
                    </td>
                    </tr>
                    ";
                }
            }

            $table = $table."<input type='hidden' name='name".$key."' value='".$name."'>";
        }

        return response()->json(array(
            'status'=>'oke',
            'msg'=>$table
        ),200);
    }
}
