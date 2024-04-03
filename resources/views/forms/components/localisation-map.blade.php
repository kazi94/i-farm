<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>

    <div
        x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
        x-init="await createMap"
    >
        <div wire:ignore id="map" style="height: 360px;"></div>

    </div>


</x-dynamic-component>


<script>
async function createMap() {
        const map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
        }).addTo(map);



    function onMapClick(e) {
        console.log(e.latlng);

        const lat = e.latlng.lat;
        const long = e.latlng.lng;

        this.state = {
            latitude: lat,
            longitude: long
        };

         const marker = L.marker(e.latlng).addTo(map);
    }

    map.on('click', onMapClick);

}
</script>
