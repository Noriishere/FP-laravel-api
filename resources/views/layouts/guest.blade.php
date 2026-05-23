<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shuttle System') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E82C2C',
                        primaryDark: '#C41F1F',
                        primaryLight: '#FF4B4B',
                        accent: '#FF8A00',
                        bg: '#F9F7F4',
                        dark: '#111010',
                        grayText: '#6B6B6B',
                        borderColor: '#E8E3DC',
                    },
                    fontFamily: {
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                        fraunces: ['Fraunces', 'serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-white text-gray-800 font-sans">

    <main>
        @yield('content')
    </main>

</body>

</html>
