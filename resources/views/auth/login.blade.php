<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library System — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white p-8 rounded shadow">
            <h1 class="text-2xl font-semibold mb-6">Library System — Login</h1>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border rounded mb-4">

                <label class="block mb-2">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded mb-4">

                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember" class="mr-2">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Sign in</button>
            </form>

            <p class="text-sm text-gray-500 mt-4">If you don't have an account, ask an administrator to create one.</p>
        </div>
    </div>
</body>
</html>
