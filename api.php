<?php

require_once('config.php');

header('Content-Type: application/json; charset=utf-8');
// Kontrola metódy HTTP požiadavky
switch ($_SERVER['REQUEST_METHOD']) {
    // Ak je metóda POST
    case "POST":

        // Dekódovanie JSON dát z tela požiadavky
        $data = json_decode(file_get_contents('php://input'), true);

        // Pokus o pripojenie k databáze
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            // V prípade chyby pri pripájaní sa k databáze sa vypíše chybová správa
            echo "Pripojenie zlyhalo: " . $e->getMessage();
        }

        // SQL príkaz na vloženie dát do tabuľky 'visits'
        $sql = "INSERT INTO visits (Country, Code, City, Locality, Time) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data['country'], $data['code'], $data['city'], $data['locality'], $data['time']]);

        break;

        // Ak je metóda GET
    case "GET":

         // Pokus o pripojenie k databáze
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo "Pripojenie zlyhalo: " . $e->getMessage();
        }

         // Kontrola, či je nastavený parameter 'country'
        if (isset($_GET['country'])) {
            $country = $_GET['country'];
      
            // SQL príkaz na získanie miest pre danú krajinu
            $sql = "SELECT City FROM visits WHERE Country = (?) GROUP BY City";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$country]);

            $city = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Odpoveď vo formáte JSON
            echo json_encode($city);
        } else if(isset($_GET['count'])) {
            $city = $_GET['count'];

            // SQL príkaz na získanie počtu návštev pre dané mesto
            $sql = "SELECT count(*) FROM visits WHERE City = (?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$city]);

            $city = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($city);
        } else {
            // Ak nie je nastavený žiadny parameter, vráti sa všetky návštevy
            $sql = "SELECT * FROM visits";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $city = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Odpoveď vo formáte JSON
            echo json_encode($city);
        }

        break;
}
?>