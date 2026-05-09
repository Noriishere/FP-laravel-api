<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">
            Reset Password
        </h1>

        <form method="POST" action="{{ url('/api/reset-password') }}">
            @csrf

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ $email }}"
                    required
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition"
            >
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>