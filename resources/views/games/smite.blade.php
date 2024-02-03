@extends('frontpage.home')

@section('title')
<title>SMITE - a Sentimet Analysis App</title>
@endsection

@section('content')
<div class="card">
  <div id="loader"></div>
  <div class="card-body" id="reviewBody">
    <h1 class="card-title">Steam Forum Search </h1>

    <form class="form-inline">
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">SMITE</div>
        </div>
        <input type="text" class="form-control mb-2 mr-sm-2" id="keywordreview" name="keywordreview" aria-describedby="keywordreview" placeholder="Enter your keyword">
      </div>
    </form>
    <button onclick="review()" class="btn btn-primary mb-2">Search</button>

    <form method="POST" action="{{route('smite.saveToTraining')}}">
      @csrf
      <table class="table" id="tableallreview" hidden>
        <thead>
          <tr>
            <th scope="col">Username</th>
            <th scope="col">Review Content</th>
            <th scope="col">Label Sentiment</th>
            <th scope="col">Category Sentiment</th>
          </tr>
        </thead>
        <tbody id="tablereview">
          
        </tbody>
      </table>
      <input type="submit" id="btnSave" value="Save" class="btn btn-primary mb-2" style="float: right;">
    </form>
  </div>
</div>

    
@endsection

@section('javascript')
<script>

$( document ).ready(function() {
    $('#loader').hide();
    $('#btnSave').hide();
});

function review()
{
  if($('#keywordreview').val() != "")
  {
    $('#loader').show();
    $('#reviewBody').css("filter","blur(6px)");
    $.ajax({
    type:'POST',
    url:'{{route("smite.getreview")}}',
    data:{'_token':'<?php echo csrf_token() ?>',
          'keyword_review': $('#keywordreview').val()
         },
    success: function(data){
      $('#reviewBody').css("filter","none");
      $('#loader').hide();
      $('#tableallreview').removeAttr('hidden');
      $("#tablereview").html(data.msg);
      $('#btnSave').show();
    }
    });
  }
  else
  {
    alert("Please input a keyword");
  }
}
</script>
@endsection