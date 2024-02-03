@extends('frontpage.home')

@section('title')
<title>LoL : Wild Rift - a Sentimet Analysis App</title>
@endsection

@section('content')
<div class="card">
  <div id="loader"></div>
  <div class="card-body" id="reviewBody">

    <h1 class="card-title">GooglePlay Reviews</h1>
    <div class="input-group input-group-sm mb-3">
      <input class="form-control" type="number" value="20" max="50" id="numberReview">
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" onclick="review()">Refresh</button>
      </div>
    </div>
    
    <form method="POST" action="{{route('wr.saveToTraining')}}">
      @csrf
      <table id="tableallreview">
        <thead>
          <tr>
            <th scope="col">Username</th>
            <th scope="col">Review Content</th>
            <th scope="col">Translated Content</th>
            <th scope="col">Label Sentiment</th>
            <th scope="col">Category Sentiment</th>
          </tr>
        </thead>
        <tbody class="table" id="tablereview">
          @foreach($result as $r)
          <tr>
            <input type="hidden" name='{{'review'.$loop->index}}' value="{{$r->translated_content}}">
            <input type="hidden" name='{{'name'.$loop->index}}' value="{{$r->userName}}">
            <td>{{$r->userName}}</td>
            <td>{{$r->content}}</td>
            <td>{{$r->translated_content}}</td>
              @if($arrLabel[$loop->index]=="Negative")
                <td id='{{"label".$loop->index}}'>
                  <select class='form-select' name='{{"sentiment".$loop->index}}' id='{{"sentiment".$loop->index}}'>
                      <option value='Positive'>Positive</option>
                      <option value='Negative' selected>Negative</option>
                      <option value='Neutral'>Neutral</option>
                  </select>
                </td>
              @elseif($arrLabel[$loop->index]=="Positive")
                <td id='{{"label".$loop->index}}'>
                  <select class='form-select' name='{{"sentiment".$loop->index}}' id='{{"sentiment".$loop->index}}'>
                      <option value='Positive' selected>Positive</option>
                      <option value='Negative'>Negative</option>
                      <option value='Neutral'>Neutral</option>
                  </select>
                </td>
              @else
                <td id='{{"label".$loop->index}}'>
                  <select class='form-select' name='{{"sentiment".$loop->index}}' id='{{"sentiment".$loop->index}}'>
                      <option value='Positive'>Positive</option>
                      <option value='Negative'>Negative</option>
                      <option value='Neutral' selected>Neutral</option>
                  </select>
                </td>
              @endif
            <td>
              @if($arrCategory[$loop->index]=="Gameplay")
                <select class='form-select' name='{{"categories".$loop->index}}' id='{{"categories".$loop->index}}'>
                  <option value='Gameplay' selected>Gameplay</option>
                  <option value='Graphics'>Graphics</option>
                  <option value='Technical'>Technical</option>
                  <option value='Sosial'>Sosial</option>
                  <option value='Others'>Others</option>
                </select>
              @elseif($arrCategory[$loop->index]=="Graphics")
                <select class='form-select' name='{{"categories".$loop->index}}' id='{{"categories".$loop->index}}'>
                  <option value='Gameplay'>Gameplay</option>
                  <option value='Graphics' selected>Graphics</option>
                  <option value='Technical'>Technical</option>
                  <option value='Sosial'>Sosial</option>
                  <option value='Others'>Others</option>
                </select>
              @elseif($arrCategory[$loop->index]=="Technical")
                <select class='form-select' name='{{"categories".$loop->index}}' id='{{"categories".$loop->index}}'>
                  <option value='Gameplay'>Gameplay</option>
                  <option value='Graphics'>Graphics</option>
                  <option value='Technical' selected>Technical</option>
                  <option value='Sosial'>Sosial</option>
                  <option value='Others'>Others</option>
                </select>
              @elseif($arrCategory[$loop->index]=="Sosial")
                <select class='form-select' name='{{"categories".$loop->index}}' id='{{"categories".$loop->index}}'>
                  <option value='Gameplay'>Gameplay</option>
                  <option value='Graphics'>Graphics</option>
                  <option value='Technical'>Technical</option>
                  <option value='Sosial' selected>Sosial</option>
                  <option value='Others'>Others</option>
                </select>
              @else
                <select class='form-select' name='{{"categories".$loop->index}}' id='{{"categories".$loop->index}}'>
                  <option value='Gameplay'>Gameplay</option>
                  <option value='Graphics' selected>Graphics</option>
                  <option value='Technical'>Technical</option>
                  <option value='Sosial'>Sosial</option>
                  <option value='Others' selected>Others</option>
                </select>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <br><br>
    <input type="submit" id="btnSave" value="Save" class="btn btn-primary mb-2" style="float: right;">
    </form>

  </div>
</div>

 
@endsection

@section('javascript')
<script>

function review()
{
  $('#loader').show();
  $('#reviewBody').css("filter","blur(6px)");
  $.ajax({
    type:'POST',
    url:'{{route("wr.getreview")}}',
    data:{'_token':'<?php echo csrf_token() ?>',
          'number': $('#numberReview').val()
         },
    success: function(data){
      $('#loader').hide();
      $('#reviewBody').css("filter","none");
      $("#tablereview").html(data.msg);
      $('#loader').hide();
    }
  });
}

$(document).ready(function(){
  $('#tableallreview').DataTable()
  $('#loader').hide();
});
</script>
@endsection