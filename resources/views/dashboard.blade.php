<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full bg-white p-8 rounded shadow">
            <h1 class="text-2xl font-semibold mb-4">Library Dashboard</h1>

            <p class="mb-4">Welcome, {{ optional(auth()->user())->name ?? optional(auth()->user())->email }}.</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
