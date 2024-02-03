@extends('frontpage.home')

@section('title')
<title>Dashboard - a Sentimet Analysis App</title>
@endsection

@section('content')
<div class="card">
    <h5 class="card-header">Training Data</h5>
    <div class="card-body">
      <table width=100%>
        <tr>
          <td><div id="piechartLabel"></div></td>
          <td><div id="piechartCategory"></div></td>
        </tr>
      </table>
      
      <table id="tabletraining">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Username</th>
            <th scope="col">Content</th>
            <th scope="col">Label</th>
            <th scope="col">Category</th>
          </tr>
        </thead>
        <tbody>
          @foreach($training as $t)
          <tr>
            <td>{{$t->id}}</td>
            <td>{{$t->username}}</td>
            <td>{{$t->content}}</td>
            <td>{{$t->label}}</td>
            <td>{{$t->category}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <a class="btn btn-secondary" href="/retrain" onclick="review()" style="float: right;">Re-Train</a>
    </div>
  </div>

@endsection

@section('javascript')
<script>
$(document).ready(function(){
  $('#tabletraining').DataTable()
});
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
<script type="text/javascript">
  // Load google charts
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChartLab);
  
  // Draw the chart and set the chart values
  function drawChartLab() {
    var data = google.visualization.arrayToDataTable([
    ['Sentiment of Dataset', 'Number of Data'],
    ['Positive', {{$numberPositive}}],
    ['Negative', {{$numberNegative}}],
    ['Neutral', {{$numberNeutral}}]
  ]);
  
    // Optional; add a title and set the width and height of the chart
    var options = {'title':'Sentiments of Dataset', 'width':500, 'height':300};
  
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechartLabel'));
    chart.draw(data, options);
  }
  </script> 

<script type="text/javascript">
  // Load google charts
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChartCat);
  
  // Draw the chart and set the chart values
  function drawChartCat() {
    var data = google.visualization.arrayToDataTable([
    ['Categories of Dataset', 'Number of Data'],
    ['Gameplay', {{$numberGameplay}}],
    ['Graphics', {{$numberGraphics}}],
    ['Technical', {{$numberTechnical}}],
    ['Social', {{$numberSosial}}],
    ['Others', {{$numberOthers}}]
  ]);
  
    // Optional; add a title and set the width and height of the chart
    var options = {'title':'Categories of Dataset', 'width':500, 'height':300};
  
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechartCategory'));
    chart.draw(data, options);
  }
  </script> 
@endsection
