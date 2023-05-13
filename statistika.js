// Vyberieme tlačidlo so triedou .countryButton
let button = document.querySelector('.countryButton')
// Po načítaní stránky
$(document).ready(function () {
    // Pridáme event listener na kliknutie na tlačidlo .countryButton
    $('.countryButton').click(function () {
        // Získame názov krajiny z textu tlačidla
        let countryName = $(this).text()
        // Odosielame GET požiadavku na server s názvom krajiny
        fetch("api.php?country=" + countryName, {
            method: "GET"
        })
            .then(response => response.json())
            .then(result => {
                console.log(result)
                // Získame element s id "layer" a zobrazíme ho
                var layer = document.getElementById("layer")
                document.getElementById("layer").style.display = 'block';
                 // Vytvárame div pre zatváranie "layer"
                var close = document.createElement("div");
                close.className = "close";
                layer.appendChild(close);
                // Vytvoríme tabuľku s výsledkami
                makeTable(layer, result);
// Pridáme event listener na kliknutie na tlačidlo close, aby sa zatvoril "layer"
                close.addEventListener("click", function () {
                    layer.style.display = 'none';
                    layer.innerHTML = '';
                });
            }
            );
    })
})

// Funkcia na vytvorenie tabuľky
function makeTable(layer, result) {
    var table = document.createElement("table");
    var tableBody = document.createElement("tbody");
    table.className = "table1";
    tableBody.className = "tablebody";
    var tableHead = document.createElement("thead");
    var th1 = document.createElement("th");
    var th2 = document.createElement("th");
    th1.innerHTML = 'Lokácia:'
    th2.innerHTML = 'Počet návštev:'

    tableHead.append(th1)
    tableHead.append(th2)
    tableHead.className = 'thead'

    result.forEach(element => {
        var row = document.createElement("tr");
        
        var cell1 = document.createElement("td");
        var cell2 = document.createElement("td");

        cell1.innerHTML = element['City']

        fetch("api.php?count=" + element['City'], {
            method: "GET"
        })
            .then(response => response.json())
            .then(result => {
                console.log(result)
                cell2.innerHTML = result[0]['count(*)']
            })

            // Pridáme bunky do riadka a riadok do tela tabuľky
        row.appendChild(cell1)
        row.appendChild(cell2)
        tableBody.appendChild(row)
    });

    // Pridáme hlavičku a telo do tabuľky a tabuľku do "layer"
    table.appendChild(tableHead)
    table.appendChild(tableBody);
    layer.appendChild(table);
}

let map;
// Funkcia na inicializáciu mapy
function initMap() {
    var maps = document.getElementById('mapa');
    maps.innerHTML = '';
    // Vytvorenie novej mapy s google maps API
    map = new google.maps.Map(document.getElementById('mapa'), {
        center: { lat: 48, lng: 0 },
        zoom: 2
    });
     // Získanie údajov zo serveru pomocou GET požiadavky
    fetch("api.php", {
        method: "GET"
    }).then(response => response.json()).then(result => {
        // Pre každý výsledok vytvárame marker na mape
        result.forEach(result => {
            console.log(result)
            var lat = result['Locality'].split(', ')[0].replace('(', '')
            var long = result['Locality'].split(', ')[1].replace(')', '')

             // Nastavenie možností pre marker
            let markerOptions = {
                position: new google.maps.LatLng(lat, long),
                map: map
            }
             // Vytvorenie nového markera na mape
            new google.maps.Marker(markerOptions);
        })
    })
}

