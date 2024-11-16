// Initialize the map and set its view to a default location and zoom level
const map = L.map('map').setView([51.505, -0.09], 13);

// Add the OpenStreetMap tiles to the map
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Create a marker to show the user's location
let marker = L.marker([51.505, -0.09]).addTo(map);

// Function to update the map with the user's current location
function updateLocation(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;

    // Update the marker position
    marker.setLatLng([lat, lon]);

    // Center the map on the new location
    map.setView([lat, lon], 13);
}

// Function to handle geolocation errors
function handleError(error) {
    console.error('Geolocation error:', error.message);
}

// Options for geolocation
const options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

// Get the user's current position and watch for changes
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(updateLocation, handleError, options);
    navigator.geolocation.watchPosition(updateLocation, handleError, options);
} else {
    alert('Geolocation is not supported by your browser');
}
