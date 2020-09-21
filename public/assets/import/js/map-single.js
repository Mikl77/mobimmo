	if ($('#map-contact').length) {

		var estate_lat = document.getElementById('estate_lat').value;
		var estate_lng =document.getElementById('estate_lng').value;

		var map = L.map('map-contact', {
			zoom: 13,
			maxZoom: 20,
			tap: false,
			gestureHandling: true,
			center: [estate_lat, estate_lng]
		});

		map.scrollWheelZoom.disable();

		var Hydda_Full = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
			scrollWheelZoom: false,
			attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		var icon = L.divIcon({
			html: '<i class="fa fa-building"></i>',
			iconSize: [50, 50],
			iconAnchor: [50, 50],
			popupAnchor: [-20, -42]
		});
// ici on met les coordonnees du bien
		var marker = L.marker([estate_lat, estate_lng], {
			icon: icon
		}).addTo(map);
	}
