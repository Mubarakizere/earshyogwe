{{-- Interactive Map Picker Component --}}
@props(['latitude' => '-1.9441', 'longitude' => '30.0619', 'name' => 'location'])

<div x-data="mapPicker{{ Str::random(5) }}()" x-init="init()" class="map-picker-container">
    <div id="map-{{ $name }}" style="height: 400px; width: 100%; border-radius: 0.5rem; border: 1px solid #e5e7eb;"></div>
    
    <!-- Hidden inputs for coordinates -->
    <input type="hidden" name="{{ $name }}_latitude" x-model="latitude" id="{{ $name }}_latitude">
    <input type="hidden" name="{{ $name }}_longitude" x-model="longitude" id="{{ $name }}_longitude">
    
    <!-- Coordinate Display -->
    <div class="mt-2 text-sm text-gray-600 flex items-center justify-between">
        <span>
            <strong>Selected:</strong> 
            <span x-text="formatCoordinates()"></span>
        </span>
        <button type="button" @click="getCurrentLocation()" class="text-purple-600 hover:text-purple-700 underline text-xs">
            Use My Current Location
        </button>
    </div>
</div>

<script>
function mapPicker{{ Str::random(5) }}() {
    return {
        map: null,
        marker: null,
        latitude: {{ $latitude }},
        longitude: {{ $longitude }},
        mapId: 'map-{{ $name }}',
        
        init() {
            // Wait for Leaflet to be loaded
            if (typeof L === 'undefined') {
                console.error('Leaflet library not loaded');
                return;
            }
            
            // Initialize map centered on Kigali, Rwanda
            this.map = L.map(this.mapId).setView([this.latitude, this.longitude], 13);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(this.map);
            
            // Add draggable marker
            this.marker = L.marker([this.latitude, this.longitude], {
                draggable: true
            }).addTo(this.map);
            
            // Update coordinates when marker is dragged
            this.marker.on('dragend', (e) => {
                const position = e.target.getLatLng();
                this.latitude = position.lat.toFixed(8);
                this.longitude = position.lng.toFixed(8);
            });
            
            // Update marker position when map is clicked
            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.latitude = e.latlng.lat.toFixed(8);
                this.longitude = e.latlng.lng.toFixed(8);
            });
        },
        
        formatCoordinates() {
            return `${parseFloat(this.latitude).toFixed(6)}, ${parseFloat(this.longitude).toFixed(6)}`;
        },
        
        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude.toFixed(8);
                        this.longitude = position.coords.longitude.toFixed(8);
                        
                        const newLatLng = L.latLng(this.latitude, this.longitude);
                        this.map.setView(newLatLng, 15);
                        this.marker.setLatLng(newLatLng);
                    },
                    (error) => {
                        alert('Could not get your location. Please select manually on the map.');
                        console.error('Geolocation error:', error);
                   }
                );
            } else {
                alert('Geolocation is not supported byy your browser.');
            }
        }
    }
}
</script>

<style>
.map-picker-container .leaflet-container {
    font-family: inherit;
}
</style>
