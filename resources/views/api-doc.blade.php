<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gassin API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #fafafa;
        }

        .swagger-ui .topbar {
            display: none;
        }
    </style>
</head>

<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js"></script>

    <script>
        window.onload = function () {
            const spec = {
                "openapi": "3.0.0",
                "info": {
                    "title": "Gassin API",
                    "version": "1.0.0",
                    "description": "Dokumentasi API interaktif lengkap untuk aplikasi Gassin. Dilengkapi dengan otorisasi JWT Bearer."
                },
                "servers": [
                    { "url": "https://gassin.naltylabs.my.id/api", "description": "Production Server" }
                ],
                "components": {
                    "securitySchemes": {
                        "bearerAuth": {
                            "type": "http",
                            "scheme": "bearer",
                            "bearerFormat": "JWT"
                        }
                    }
                },
                "security": [{ "bearerAuth": [] }],
                "tags": [
                    { "name": "Public", "description": "Endpoint yang dapat diakses tanpa login" },
                    { "name": "Auth", "description": "Otentikasi & Profil (User & Driver)" },
                    { "name": "Schedules & Seats", "description": "Jadwal dan Ketersediaan Kursi" },
                    { "name": "Customer", "description": "Endpoint khusus role: customer" },
                    { "name": "Driver", "description": "Endpoint khusus role: driver" },
                    { "name": "Payment", "description": "Transaksi & Webhook Pembayaran" },
                    { "name": "Vehicles", "description": "Data Kendaraan" }
                ],
                "paths": {
                    "/": { "get": { "tags": ["Public"], "summary": "Welcome API", "security": [], "responses": { "200": { "description": "Welcome message" } } } },
                    "/register": { "post": { "tags": ["Public"], "summary": "Register Customer", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "name": { "type": "string" }, "email": { "type": "string" }, "password": { "type": "string" } } } } } }, "responses": { "201": { "description": "Success" } } } },
                    "/login": { "post": { "tags": ["Public"], "summary": "Login Customer", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "email": { "type": "string" }, "password": { "type": "string" } } } } } }, "responses": { "200": { "description": "Token JWT" } } } },
                    "/email/resend": { "post": { "tags": ["Public"], "summary": "Resend Email Verification", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "email": { "type": "string" } } } } } }, "responses": { "200": { "description": "Email sent" } } } },
                    "/drivers/login": { "post": { "tags": ["Public"], "summary": "Login Driver", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "email": { "type": "string" }, "password": { "type": "string" } } } } } }, "responses": { "200": { "description": "Token JWT" } } } },
                    "/forgot-password": { "post": { "tags": ["Public"], "summary": "Forgot Password", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "email": { "type": "string" } } } } } }, "responses": { "200": { "description": "Success" } } } },
                    "/reset-password": { "post": { "tags": ["Public"], "summary": "Reset Password", "security": [], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "token": { "type": "string" }, "email": { "type": "string" }, "password": { "type": "string" }, "password_confirmation": { "type": "string" } } } } } }, "responses": { "200": { "description": "Success" } } } },
                    "/refresh": { "post": { "tags": ["Auth"], "summary": "Refresh JWT Token", "responses": { "200": { "description": "Token JWT Baru" } } } },
                    "/me": {
                        "get": { "tags": ["Auth"], "summary": "Get Profil Saya", "responses": { "200": { "description": "Data Profile" } } },
                        "put": { "tags": ["Auth"], "summary": "Update Profil Saya", "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "name": { "type": "string" }, "password": { "type": "string" } } } } } }, "responses": { "200": { "description": "Updated" } } }
                    },
                    "/logout": { "post": { "tags": ["Auth"], "summary": "Logout", "responses": { "200": { "description": "Success" } } } },

                    "/schedules/search": { "get": { "tags": ["Schedules & Seats"], "summary": "Search Schedules", "security": [], "parameters": [{ "name": "origin", "in": "query", "schema": { "type": "string" } }, { "name": "destination", "in": "query", "schema": { "type": "string" } }], "responses": { "200": { "description": "List" } } } },
                    "/schedules": { "get": { "tags": ["Schedules & Seats"], "summary": "All Schedules", "security": [], "parameters": [{ "name": "origin", "in": "query", "schema": { "type": "string" } }, { "name": "destination", "in": "query", "schema": { "type": "string" } }], "responses": { "200": { "description": "List" } } } },
                    "/schedules/sorted": { "get": { "tags": ["Schedules & Seats"], "summary": "Sorted Schedules", "security": [], "parameters": [{ "name": "origin", "in": "query", "schema": { "type": "string" } }, { "name": "destination", "in": "query", "schema": { "type": "string" } }, { "name": "direction", "in": "query", "schema": { "type": "string", "enum": ["asc", "desc"] } }], "responses": { "200": { "description": "List" } } } },
                    "/schedules/sortedByDay": { "get": { "tags": ["Schedules & Seats"], "summary": "Sorted Schedules By Day", "security": [], "parameters": [{ "name": "origin", "in": "query", "schema": { "type": "string" } }, { "name": "destination", "in": "query", "schema": { "type": "string" } }, { "name": "origin_date", "in": "query", "schema": { "type": "string", "format": "date" } }, { "name": "destination_date", "in": "query", "schema": { "type": "string", "format": "date" } }, { "name": "from_date", "in": "query", "schema": { "type": "string", "format": "date" } }, { "name": "to_date", "in": "query", "schema": { "type": "string", "format": "date" } }, { "name": "direction", "in": "query", "schema": { "type": "string", "enum": ["asc", "desc"] } }], "responses": { "200": { "description": "List" } } } },
                    "/schedules/{id}": { "get": { "tags": ["Schedules & Seats"], "summary": "Detail Schedule", "security": [], "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Detail" } } } },
                    "/schedules/{id}/seats": { "get": { "tags": ["Schedules & Seats"], "summary": "Seat Availability", "security": [], "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Seats data" } } } },
                    "/schedules/{id}/check-seat": { "post": { "tags": ["Schedules & Seats"], "summary": "Check Seat Status", "security": [], "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "seat_id": { "type": "integer" }, "pickup_stop_id": { "type": "integer" }, "dropoff_stop_id": { "type": "integer" } } } } } }, "responses": { "200": { "description": "Status" } } } },
                    "/schedules/{id}/map": { "get": { "tags": ["Schedules & Seats"], "summary": "Map Route Schedule", "security": [], "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Data map" } } } },

                    "/bookings": { "post": { "tags": ["Customer"], "summary": "Store Booking", "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "schedule_id": { "type": "integer" }, "pickup_stop_id": { "type": "integer" }, "dropoff_stop_id": { "type": "integer" }, "seat_ids": { "type": "array", "items": { "type": "integer" } } } } } }, "responses": { "200": { "description": "Success" } } } }},
                    "/bookings/{id}": { "get": { "tags": ["Customer"], "summary": "Detail Booking", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Detail" } } } },
                    "/me/bookings": { "get": { "tags": ["Customer"], "summary": "My Bookings History", "responses": { "200": { "description": "List" } } } },
                    "/me/booking/detail/{id}": { "get": { "tags": ["Customer"], "summary": "My Booking Detail", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Detail" } } } },
                    "/schedules/{id}/tracking": { "get": { "tags": ["Customer"], "summary": "Track Schedule (Customer)", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Tracking Data" } } } },
                    "/schedules/{id}/route": { "get": { "tags": ["Customer"], "summary": "Schedule Route (Customer)", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Route Data" } } } },

                    "/vehicles": { "get": { "tags": ["Vehicles"], "summary": "Get All Vehicles", "responses": { "200": { "description": "List" } } } },
                    "/vehicles/{id}": { "get": { "tags": ["Vehicles"], "summary": "Detail Vehicle", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Detail" } } } },

                    "/payment/create": { "post": { "tags": ["Payment"], "summary": "Create Payment QRIS", "requestBody": { "required": true, "content": { "application/json": { "schema": { "type": "object", "properties": { "booking_id": { "type": "integer" } } } } } }, "responses": { "200": { "description": "Payment Detail" } } } },
                    "/payment/callback": { "post": { "tags": ["Payment"], "summary": "Payment Callback", "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "order_id": { "type": "string" }, "status": { "type": "string" } } } } } }, "responses": { "200": { "description": "Success" } } } },
                    "/payment/cancel": { "post": { "tags": ["Payment"], "summary": "Cancel Payment/Booking", "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "booking_id": { "type": "integer" } } } } } }, "responses": { "200": { "description": "Cancelled" } } } },
                    "/payment/check/{bookingId}": { "get": { "tags": ["Payment"], "summary": "Check Payment Transaction", "parameters": [{ "name": "bookingId", "in": "path", "required": true, "schema": { "type": "string" } }], "responses": { "200": { "description": "Status" } } } },
                    "/payment/pakasir/webhook": { "post": { "tags": ["Payment"], "summary": "Webhook dari Pakasir", "security": [], "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "order_id": { "type": "string" }, "amount": { "type": "integer" }, "status": { "type": "string" } } } } } }, "responses": { "200": { "description": "Processed" } } } },

                    "/scan-booking": { "post": { "tags": ["Driver"], "summary": "Scan Tiket Booking", "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "order_id": { "type": "string" } } } } } }, "responses": { "200": { "description": "Valid" } } } },
                    "/me/schedules": { "get": { "tags": ["Driver"], "summary": "My Schedules", "responses": { "200": { "description": "List" } } } },
                    "/driver/me": { "get": { "tags": ["Driver"], "summary": "Get Profil Driver", "responses": { "200": { "description": "Profile & Documents" } } } },
                    "/driver/schedules": { "get": { "tags": ["Driver"], "summary": "Schedules Milik Driver", "responses": { "200": { "description": "List" } } } },
                    "/driver/history": { "get": { "tags": ["Driver"], "summary": "History Trip Driver", "responses": { "200": { "description": "History" } } } },
                    "/driver/schedules/{id}/route": { "get": { "tags": ["Driver"], "summary": "Detail Route Driver", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Detail" } } } },
                    "/driver/schedules/{id}/start": { "post": { "tags": ["Driver"], "summary": "Start Perjalanan", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "latitude": { "type": "number" }, "longitude": { "type": "number" }, "speed": { "type": "number" }, "heading": { "type": "number" }, "accuracy": { "type": "number" }, "is_mocked": { "type": "boolean" } } } } } }, "responses": { "200": { "description": "Started" } } } },
                    "/driver/schedules/{id}/location": { "post": { "tags": ["Driver"], "summary": "Update Lokasi Driver (Throttle)", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "requestBody": { "content": { "application/json": { "schema": { "type": "object", "properties": { "latitude": { "type": "number" }, "longitude": { "type": "number" }, "speed": { "type": "number" }, "heading": { "type": "number" }, "accuracy": { "type": "number" }, "is_mocked": { "type": "boolean" } } } } } }, "responses": { "200": { "description": "Updated" } } } },
                    "/driver/schedules/{id}/stop": { "post": { "tags": ["Driver"], "summary": "Selesaikan Perjalanan (Stop)", "parameters": [{ "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }], "responses": { "200": { "description": "Stopped" } } } }
                }
            }
            const ui = SwaggerUIBundle({
                spec: spec,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "BaseLayout"
            });
            window.ui = ui;
        }
    </script>
</body>

</html>