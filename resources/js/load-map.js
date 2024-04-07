document.addEventListener("DOMContentLoaded", function() {
    const map = L.map('map').setView([51.505, -0.09], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
    }).addTo(map);



    function onMapClick(e) {

        document.querySelector('#locationInput').value = e.latlng;

        const marker = L.marker(e.latlng).addTo(map);
    }

    map.on('click', onMapClick);
});

