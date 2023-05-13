<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>


<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Počasie</title>
</head>

<body>

    <div class="row">
        <div class="col-12 bg-clip-content text-black text-center">
            <header>
                <h1>Predpoveď počasia</h1>
            </header>
        </div>
    </div>


    <div class="row">
        <nav class="col-12 bg-clip-content text-black text-center bg-primary">
            <ul class="row justify-content-center">
                <div class="col-4 d-grid">
                    <li>
                        <a href="index.php">Adresa</a>
                    </li>
                </div>

    
                <div class="col-4 d-grid">
                    <li>
                        <a href="statistika.php">Návštevnosť</a>
                    </li>
                </div>
            </ul>
        </nav>
    </div>



    <div class="page-content p-3">
        <div class="col-12 bg-clip-content text-black text-center">
            <h2>Adresa</h2>
        </div>

        

        <form class="form" action="index.php" method="post">

            <div class="row mb-3 input-group">
                <div class="col-6">
                    <label for="pac-input" class="form-label">Zadaj adresu</label>
                    <input type="text" name="address" id="pac-input" class="form-control controls" >
                </div>
                <div class="col-6 d-grid  input-group-append">

                    <input id="btn" type="button" name="submit" class="btn btn-success" value="Vyhľadaj">
                </div>
            </div>
        </form>

        <div class="row justify-content-center">
            <div class="col-12 pe-md-5 col-md-4 order-1 order-md-0">
                <div id="pocasie" class="row d-flex justify-content-center">
                    <div><b>Stav oblohy:</b></div>
                    <div id="obloha"></div><br>
                    <div><b>Teplota</b></div>
                    <div id="teplota"></div><br>
                    <div><b>Rýchlosť vetra</b></div>
                    <div id="vietor"></div><br>
                </div>
            </div>

            <div class="col-12 col-md-4 order-0 order-md-1">
                <div class="row d-flex justify-content-center">
                    
                </div>
                <div class="row d-flex justify-content-center">
                    <div id="infomacie" class="col-auto">
                        <div><b>GPS lokácia: </b></div>
                        <div id="lokacia"></div><br>
                        <div><b>Krajina: </b></div>
                        <div id="krajina"></div><br>
                        <div><b>Hlavné mesto: </b></div>
                        <div id="hlavne"></div><br>
                        <div id="mesto"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <div class="row">
        <div class="col-12 bg-clip-content text-white text-center bg-primary">
            <footer class="px-2">
                Michal Belan, &copy; 2023
            </footer>
        </div>

    </div>


    <script src="script.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2MeRgRdBzfHem-vpeT196rvW3fwZyNWc&callback=initMap&libraries=places&v=weekly" async></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>