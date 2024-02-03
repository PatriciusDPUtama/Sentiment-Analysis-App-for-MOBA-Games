<!DOCTYPE html>
<html lang="en">
<head>
    @yield('title')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css"/>
    <link rel="stylesheet" href="{{asset('sidebar/style.css')}}" />
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.7/datatables.min.css" rel="stylesheet">
 
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://kit.fontawesome.com/7a5676d53c.js" crossorigin="anonymous"></script>
    
    @yield('javascript')
</head>
<body>

    <div id="wrapper" class="toggled">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <p style="color:white;">
                        Sentiment Analysis App
                    </p>
                </li>
                <li>
                  <hr style="color: white;">
                  <div class="accordion" id="accordionHome">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingHome">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHome" aria-expanded="true" aria-controls="collapseHome">
                          <i class="fa-solid fa-house" style="font-size:36px;margin:0px 12px"></i>Dashboard
                        </button>
                      </h2>
                      <div id="collapseHome" class="accordion-collapse collapse" aria-labelledby="headingHome" data-bs-parent="#accordionHome">
                        <div class="accordion-body">
                          <a href="/" class="sidebar-menu"><i class="fa-solid fa-house" style="margin:0px 6px"></i>Home</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                    <div class="accordion" id="accordionGame">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              <i class="fa-gamepad fa-solid" style="font-size:36px;margin:0px 12px"></i>Game Reviews
                            </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionGame">
                            <div class="accordion-body">
                                <a href="dota2" class="sidebar-menu"><img src="{{asset('images/dota2icon.png')}}"> Dota 2</a>
                                <a href="smite" class="sidebar-menu"><img src="{{asset('images/smiteicon.png')}}"> SMITE</a>
                                <a href="mlbb" class="sidebar-menu"><img src="{{asset('images/mlicon.png')}}"> Mobile Legends</a>
                                <a href="wildrift" class="sidebar-menu"><img src="{{asset('images/wildrifticon.png')}}"> Wild Rift</a>
                                <a href="pokemon" class="sidebar-menu"><img src="{{asset('images/pokemonicon.png')}}"> Pokémon Unite</a>
                            </div>
                          </div>
                        </div>
                      </div>
                </li>
                <li>
                    <div class="accordion" id="accordionTesting">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                              <i class="fa-solid fa-magnifying-glass-chart" style="font-size:36px;margin:0px 12px"></i>Testing
                            </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionTesting">
                            <div class="accordion-body">
                              <a href="testing" class="sidebar-menu"><i class="fa-solid fa-magnifying-glass" style="margin:0px 6px"></i>Classification</a>
                            </div>
                          </div>
                        </div>
                      </div>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <script>
    var header = document.getElementById("sidebar-wrapper");
    var btns = header.getElementsByClassName("sidebar-menu");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active");
            if (current.length > 0) {
                current[0].className = current[0].className.replace(" active", "");
            }
            this.className += " active";
        });
    }
    </script>

    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/cdb.min.js"></script>

</body>
</html>