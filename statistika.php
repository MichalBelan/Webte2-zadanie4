<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


require_once("config.php");

try {
    // Vytvorenie nového PDO objektu pre pripojenie k databáze
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Nastavenie režimu spracovania chýb
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // V prípade chyby pri pripájaní sa k databáze sa vypíše chybová správa
    echo "Pripojenie zlyhalo: " . $e->getMessage();
}

// SQL príkaz na získanie jedinečných hodnôt krajín z tabuľky 'visits'
$sql = "SELECT Country FROM visits GROUP BY Country";

$stmt = $conn->prepare($sql);
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// SQL príkaz na získanie jedinečných hodnôt kódov z tabuľky 'visits'
$sql = "SELECT Code FROM visits GROUP BY Code";

$stmt = $conn->prepare($sql);
$stmt->execute();
$codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// SQL príkaz na získanie počtu návštev podľa krajiny
$sql = "SELECT count(*) FROM visits GROUP BY Country";

$stmt = $conn->prepare($sql);
$stmt->execute();
$counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// SQL príkazy na získanie počtu návštev podľa časových intervalov
$sql = "SELECT count(*) FROM visits WHERE HOUR(`Time`) BETWEEN '6:00:00' AND '15:00:00'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$time1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT count(*) FROM visits WHERE HOUR(`Time`) BETWEEN '15:00:00' AND '21:00:00'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$time2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT count(*) FROM visits WHERE HOUR(`Time`) BETWEEN '21:00:00' AND '24:00:00'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$time3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT count(*) FROM visits WHERE HOUR(`Time`) BETWEEN '00:00:00' AND '06:00:00'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$time4 = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Štatistika</title>
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
            <h2>Návštevnosť podľa krajín</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-auto">
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>Krajina</th>
                            <th>Vlajka</th>
                            <th>Počet návštev</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if (count($countries) != 0) {
                            $i = 0;
                            foreach ($countries as $country) {
                                $imagelink = 'https://www.geonames.org/flags/x/' . strtolower($codes[$i]['Code']) . '.gif';
                                $altcode = $codes[$i]['Code'];
                                echo "<tr>
                                        <td><button class='btn countryButton' id='countryButton'>" . $country['Country'] . "</button></td>
                                        <td><img src=$imagelink alt=$altcode border=1 height=30 width=50></img></td>
                                        <td>{$counts[$i]['count(*)']}</td>
                                    </tr>";
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row mt-5 mx-2">
            <h4 class="ps-0">Časová návštevnosť</h4>
            <table class="table">
                <thead>
                    <th>Čas</th>
                    <th>Počet návštev</th>
                </thead>
                <tbody>
                    <tr>
                        <td>6:00-15:00</td>
                        <?php
                        echo " <td>{$time1[0]['count(*)']}</td>";
                        ?>
                    </tr>
                    <tr>
                        <td>15:00-21:00</td>
                        <?php
                        echo " <td>{$time2[0]['count(*)']}</td>";
                        ?>
                    </tr>
                    <tr>
                        <td>21:00-24:00</td>
                        <?php
                        echo " <td>{$time3[0]['count(*)']}</td>";
                        ?>
                    </tr>
                    <tr>
                        <td>24:00-6:00</td>
                        <?php
                        echo " <td>{$time4[0]['count(*)']}</td>";
                        ?>
                    </tr>


                </tbody>
            </table>
        </div>



        <div class="row my-4 mx-2 justify-content-center">
            <div class="col-auto">
                <h4 class="ps-0">Lokácia návštev</h4>
            </div>
            <div class="col-auto">
                <div id="mapa" class="mapa"  style="height: 400px"></div>
              
            </div>
        </div>

    </div>

    <div id="layer" class="layer"></div>



    <div class="row">
        <div class="col-12 bg-clip-content text-white text-center bg-primary">
            <footer class="px-2">
                Michal Belan, &copy; 2023
            </footer>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2MeRgRdBzfHem-vpeT196rvW3fwZyNWc&callback=initMap&libraries=places&v=weekly" async></script>
    <script src="statistika.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
   
</body>

</html>