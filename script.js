// Selektovanie rôznych HTML elementov na stránke
var infoBox = document.querySelector("#infomacie");
var weatherBox = document.querySelector("#pocasie");
const button = document.querySelector("#btn");
infoBox.style.visibility = "hidden"
weatherBox.style.visibility = "hidden"
var gps = document.querySelector("#lokacia");
var country = document.querySelector("#krajina");
var capital = document.querySelector("#hlavne");
var city = document.querySelector("#mesto");
var sky = document.querySelector("#obloha");
var temperature = document.querySelector("#teplota");
var wind = document.querySelector("#vietor");
var places = [];

city.style.visibility = "hidden"
// Funkcia na inicializáciu Google Mapy
function initMap() {
   


    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input);
   // Pridanie listenera, ktorý sleduje zmeny v miestach
    searchBox.addListener("places_changed", () => {
        places = searchBox.getPlaces();
    })
}

window.initMap = initMap;
// Pridanie listenera na tlačidlo, ktoré zobrazí informácie o mieste
button.addEventListener('click', () => {

   
    places.forEach((place) => {
        // Nastavenie mesta na prvý adresný komponent miesta
        city.innerHTML = place.address_components[0].long_name
        weatherBox.style.visibility = "visible"
        
        const apiKey = 'd466e6250aabf11afc4d79908617d2f8'
        

        let size = place.address_components.length
        var countryName = 0
        // Získanie názvu krajiny
        if (!hasNumber(place.address_components[size - 1].long_name)) {
            countryName = place.address_components[size - 1].long_name
        } else {
            countryName = place.address_components[size - 2].long_name
        }

        // Získanie polohy miesta
        var lat = place.geometry.location.lat()
        var long = place.geometry.location.lng()

        // Fetch počasia pre danú polohu z OpenWeatherMap API
        fetch('https://api.openweathermap.org/data/2.5/weather?lat=' + lat + '&lon=' + long + '&appid=' + apiKey)
            .then(res => res.json())
            .then(data => {
                // Nastavenie informácií o počasí
                sky.innerHTML = data['weather'][0]['description']
                temperature.innerHTML = convertTemp(data['main']['temp']) + ' °C'
                wind.innerHTML = data['wind']['speed'] + ' km/h'
            })

        infoBox.style.visibility = "visible"
        gps.innerHTML = place.geometry.location;
        country.innerHTML = countryName

        var shortCut = 0
        if (!hasNumber(place.address_components[size - 1].short_name)) {
            shortCut = place.address_components[size - 1].short_name
        } else {
            shortCut = place.address_components[size - 2].short_name
        }

        // Fetch informácií o krajine z RestCountries API
        fetch('https://restcountries.com/v2/alpha/' + shortCut)
            .then(response => response.json())
            .then(data => {
                // Nastavenie hlavného mesta krajiny
                capital.innerHTML = data['capital']
            });

             // Získanie aktuálneho dátumu a času
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = "0" + dd
        }

        if (mm < 10) {
            mm = "0" + mm
        }

        var currentDate = yyyy + "-" + mm + "-" + dd + " " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

        // Odoslanie POST požiadavky na "api.php" s informáciami o mieste
        fetch("api.php", {
            method: "POST",
            body: JSON.stringify({
                "country": countryName,
                "code": shortCut,
                "city": place.address_components[0].long_name,
                "locality": place.geometry.location.toString(),
                "time": currentDate
            })
        }).then(response => response.json()).then(result => JSON.stringify(result, undefined, 4));
    });
})
// Funkcia pre konverziu teploty z Kelvinov na Celsius
function convertTemp(value) {
    return (value - 273).toFixed(2)
}

// Funkcia pre kontrolu, či reťazec obsahuje číslo
function hasNumber(myString) {
    return /\d/.test(myString);
}
