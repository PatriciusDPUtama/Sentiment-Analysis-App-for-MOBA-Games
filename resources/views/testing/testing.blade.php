@extends('frontpage.home')

@section('title')
<title>Testing - a Sentimet Analysis App</title>
@endsection

@section('content')

<div class="card">
  <div id="loader"></div>
  <div class="card-body" id="testingbody">
      <h1 class="card-title">Test Classification</h1>
      <div class="input-group mb-3">
        <input type="text" class="form-control" id="testtext" name="testtext" placeholder="Enter the text" aria-label="textEnter" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" onclick="classify()" type="button">Classify</button>
        </div>
      </div>
    <h2><p id="result"></p></h2>
  </div>
</div>
@endsection

@section('javascript')
<script>
$( document ).ready(function() {
    $('#loader').hide();
});

function classify()
{
  if($('#testtext').val() != "")
  {
    $("#result").html("");
    $('#loader').show();
    $('#testingbody').css("filter","blur(6px)");
    $.ajax({
    type:'POST',
    url:'{{route("test.testReview")}}',
    data:{'_token':'<?php echo csrf_token() ?>',
          'testText': $('#testtext').val()
         },
    success: function(data){
      $('#loader').hide();
      $('#testingbody').css("filter","none");
      $("#result").html(data.msg);
    }
  });
  }
  else{
    alert("Please input a keyword");
  }
  
}

</script>
@endsection