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

                marker = L.marker(
                    [lat, lng]
                ).addTo(map);

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

                if (
                    data.schedule?.route?.polyline &&
                    data.schedule.route.polyline.length
                ) {

                    data.schedule.route.stops.forEach(stop => {

                        const stopMarker = L.marker([
                            stop.latitude,
                            stop.longitude
                        ]).addTo(map);

                        stopMarker.bindPopup(`
                            <div>

                                <h3 style="font-weight:bold;">
                                    ${stop.name}
                                </h3>

                                <p>
                                    Status:
                                    ${stop.status}
                                </p>

                            </div>
                        `);

                        stopMarkers.push(stopMarker);
                    });
                }

                /*
                |--------------------------------------------------------------------------
                | Polyline
                |--------------------------------------------------------------------------
                */

                if (
                    data.schedule?.route?.stops &&
                    data.schedule.route.stops.length
                ) {

                    routeLine = L.polyline(
                        data.schedule.route.polyline, {
                            color: 'blue',
                            weight: 5,
                        }
                    ).addTo(map);

                    map.fitBounds(
                        routeLine.getBounds()
                    );
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
