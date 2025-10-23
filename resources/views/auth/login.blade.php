<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Log Masuk</h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.send') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Emel</label>
                <input type="email" name="email" id="email" required
                       class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                       placeholder="cth: ali@email.com">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Hantar Kod TAC
            </button>
        </form>
    </div>

</body>
</html>
