<div class="bg-white rounded-2xl shadow overflow-hidden">

    <div class="p-5 border-b">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-bold text-gray-800">
                    Live Tracking Map
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Realtime driver location monitoring
                </p>

            </div>

            <div id="tracking-status" class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-medium">
                LIVE
            </div>

        </div>

    </div>

    <div id="map" class="w-full h-[500px]"></div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map;

    let marker;

    let routeLine;

    let stopMarkers = [];

    async function loadTrackingMap() {

        try {

            const response = await fetch(
                "/admin/trip-monitoring/{{ $scheduleId }}/data"
            );
            // Custom icon bus
            const busIcon = L.divIcon({
                className: '',
                html: `
        <div style="
            background: #2563eb;
            border: 3px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="white">
                <path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h8v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm9 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM6 10V6h12v4H6z"/>
            </svg>
        </div>
    `,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -22],
            });

            // Fungsi buat stop icon dengan nomor urutan
            function createStopIcon(order) {
                return L.divIcon({
                    className: '',
                    html: `
            <div style="
                background: #ffffff;
                border: 3px solid #2563eb;
                border-radius: 50%;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                font-size: 13px;
                font-weight: 700;
                color: #2563eb;
                font-family: sans-serif;
            ">${order}</div>
        `,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -18],
                });
            }
            const result = await response.json();

            if (!result.success) {

                document.getElementById(
                    'tracking-status'
                ).innerHTML = 'OFFLINE';

                document.getElementById(
                        'tracking-status'
                    ).className =
                    'bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-medium';

                return;
            }

            const data = result.data;

            const lat = parseFloat(
                data.latitude
            );

            const lng = parseFloat(
                data.longitude
            );

            if (!map) {

                map = L.map('map').setView(
                    [lat, lng],
                    13
                );

                L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }
                ).addTo(map);

                marker = L.marker([lat, lng], {
                    icon: busIcon
                }).addTo(map);

                marker.bindPopup(`
                    <div>

                        <h3 style="font-weight:bold;">
                            Driver Location
                        </h3>

                        <p>
                            Speed:
                            ${data.speed ?? 0} km/h
                        </p>

                    </div>
                `);

                /*
                |--------------------------------------------------------------------------
                | Stops
                |--------------------------------------------------------------------------
                */
                if (data.schedule?.route?.stops) {
                    data.schedule.route.stops.forEach(stop => {
                        if (!stop.latitude || !stop.longitude) return;

                        const stopMarker = L.marker([
                            parseFloat(stop.lat),
                            parseFloat(stop.lng)
                        ], {
                            icon: createStopIcon(stop.order)
                        }).addTo(map);

                        stopMarker.bindPopup(`
            <div style="font-family: sans-serif; min-width: 130px;">
                <p style="font-weight: bold; margin: 0 0 4px;">${stop.name}</p>
                <p style="margin: 0; color: #666; font-size: 12px;">${stop.address ?? '-'}</p>
            </div>
        `);

                        stopMarkers.push(stopMarker);
                    });
                }

                if (
                    data.schedule?.route?.polyline &&
                    data.schedule.route.polyline.length
                ) {
                    // Parse string JSON jadi array
                    let polylineData = data.schedule.route.polyline;
                    if (typeof polylineData === 'string') {
                        polylineData = JSON.parse(polylineData);
                    }

                    const validPolyline = polylineData.filter(coord =>
                        coord && coord[0] !== null && coord[1] !== null
                    );

                    if (validPolyline.length > 1) {
                        routeLine = L.polyline(validPolyline, {
                            color: 'blue',
                            weight: 5,
                        }).addTo(map);

                        map.fitBounds(routeLine.getBounds());
                    }
                }

            } else {

                marker.setLatLng([
                    lat,
                    lng
                ]);

                marker.setPopupContent(`
                    <div>

                        <h3 style="font-weight:bold;">
                            Driver Location
                        </h3>

                        <p>
                            Speed:
                            ${data.speed ?? 0} km/h
                        </p>

                    </div>
                `);

                map.panTo([
                    lat,
                    lng
                ]);
            }

        } catch (error) {

            console.error(error);
        }
    }

    loadTrackingMap();

    setInterval(() => {

        loadTrackingMap();

    }, 5000);
</script>
