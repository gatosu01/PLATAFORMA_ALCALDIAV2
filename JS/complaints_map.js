// Inicializar mapa
var map = L.map('map').setView([8.0990, -80.9650], 13); // Centrado en santiago de Veraguas
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

var marker;

// Capturar clic en el mapa
map.on('click', function(e) {
  var lat = e.latlng.lat;
  var lng = e.latlng.lng;
  document.getElementById('lat').value = lat;
  document.getElementById('lng').value = lng;

  if (marker) map.removeLayer(marker);
  marker = L.marker([lat, lng]).addTo(map);
});

// Botón para usar ubicación actual
document.getElementById('btn-ubicacion').addEventListener('click', function() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
      var lat = pos.coords.latitude;
      var lng = pos.coords.longitude;
      document.getElementById('lat').value = lat;
      document.getElementById('lng').value = lng;

      map.setView([lat, lng], 16);
      if (marker) map.removeLayer(marker);
      marker = L.marker([lat, lng]).addTo(map);
    }, function() {
      alert('No se pudo obtener la ubicación.');
    });
  } else {
    alert('La geolocalización no está soportada en este navegador.');
  }
});
