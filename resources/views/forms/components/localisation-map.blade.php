<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">

    <div class="justify-end flex gap-2">
        <x-filament::button onclick="openGoogleMap()" color="success" icon="heroicon-s-arrow-top-right-on-square">
            Direction
        </x-filament::button>
        <x-filament::button onclick="changeToSattelite()" color="gray" icon="heroicon-s-globe-asia-australia">
            Satellite
        </x-filament::button>
        <x-filament::button onclick="changeToMap()" color="info" icon="heroicon-s-map">
            map
        </x-filament::button>
    </div>
    <div x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }} }"
        x-init="await createMap" wire:ignore>



        <div id="map" style="height: 540px;"></div>

    </div>


</x-dynamic-component>


<script>
    var map = L.map('map').setView([34.87461822652609, -1.3095474251895214], 13);



    async function changeToSattelite() {
        var mapLink = '<a href="http://www.esri.com/">Esri</a>';
        var wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

        L.tileLayer(
            'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; ' + mapLink + ', ' + wholink,
            maxZoom: 18,
        }).addTo(map);
    }

    function changeToMap() {
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
    }

    async function createMap() {
        const latitude = @js($getRecord()->latitude ?? 0);
        const longitude = @js($getRecord()->longitude ?? 0);
        // const map = L.map('map').setView([34.87461822652609, -1.3095474251895214], 13);
        var mapLink = '<a href="http://www.esri.com/">Esri</a>';
        var wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

        // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19,
        //     attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
        // }).addTo(map);

        L.tileLayer(
            'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; ' + mapLink + ', ' + wholink,
            maxZoom: 18,
        }).addTo(map);
        let marker = L.marker();

        if (latitude && longitude) {
            marker = L.marker([latitude, longitude]).addTo(map);
        }
        const onMapClick = (e) => {
            marker.remove();

            const lat = e.latlng.lat;
            const long = e.latlng.lng;

            this.state = {
                'latitude': lat,
                'longitude': long
            };

            marker = L.marker(e.latlng);
            marker.addTo(map);
            //marker.remove();
        }

        map.on('click', onMapClick);

    }


    async function openGoogleMap() {
        var lat = @js($getRecord()->latitude ?? 0);
        var lng = @js($getRecord()->longitude ?? 0);
        var url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
        window.open(url, '_blank');
    }
</script>
