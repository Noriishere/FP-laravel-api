<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GASSIN')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>

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

<body class="bg-bg text-dark font-jakarta overflow-x-hidden">

    @include('layouts.landing-navigation')

    @yield('content')

</body>
</html>