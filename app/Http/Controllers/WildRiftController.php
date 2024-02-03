<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartingData;
class WildRiftController extends Controller
{
    //
    public function index(){
        set_time_limit(3600);
        $result = CrawlerController::getGoogleplay('com.riotgames.league.wildrift',3);

        $arrLabel = [];
        $arrCategory = [];
        
        foreach($result as $res)
        {
            $text = $res->content;
            $text = preg_replace('/[^A-Za-z0-9 ]/','', $text);

            $res->content = str_replace("'","",$res->content);
            $res->translated_content = str_replace("'","",$res->translated_content);

            $classificationResult = MachineLearningController::classifyReview($text);

            $label = trim($classificationResult['label']);
            $category = trim($classificationResult['category']);

            array_push($arrLabel,$label);  
            array_push($arrCategory,$category); 
        }
        
        return view('games.ml',compact('result','arrLabel','arrCategory'));
    }

    public function saveToTraining(Request $request){
        $input = $request->except(['_token','tableallreview_length']);
    
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
        $number = $_POST['number'];

        $result = CrawlerController::getGoogleplay('com.riotgames.league.wildrift',$number);

        $arrLabel = [];
        $arrCategory = [];
        foreach($result as $res)
        {
            $text = $res->content;
            $text = preg_replace('/[^A-Za-z0-9 ]/','', $text);

            $res->content = str_replace("'","",$res->content);
            $res->translated_content = str_replace("'","",$res->translated_content);

            $classificationResult = MachineLearningController::classifyReview($text);

            $label = trim($classificationResult['label']);
            $category = trim($classificationResult['category']);

            array_push($arrLabel,$label);  
            array_push($arrCategory,$category); 
        }
        
        $table = "";
        

        foreach($result as $key=>$res){

            if($arrLabel[$key]=="Negative")
            {
                if($arrCategory[$key]=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
            elseif ($arrLabel[$key]=="Positive") 
            {
                if($arrCategory[$key]=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                if($arrCategory[$key]=="Gameplay")
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Graphics") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Technical") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                elseif ($arrCategory[$key]=="Social") 
                {
                    $table = $table."
                    <tr>
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
                    <td>".$res->userName."</td>
                    <td>".$res->content."</td>
                    <td>".$res->translated_content."</td>
                    <input type='hidden' name='review".$key."' value='".$res->translated_content."'>
                    <input type='hidden' name='name".$key."' value='".$res->userName."'>
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
        }

        return response()->json(array(
            'status'=>'oke',
            'msg'=>$table
        ),200);
    }
}
