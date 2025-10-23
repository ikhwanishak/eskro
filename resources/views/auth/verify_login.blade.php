<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sahkan Kod TAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Sahkan Kod TAC</h2>

        <p class="text-sm text-gray-600 mb-4 text-center">
            Kod TAC telah dihantar ke <strong>{{ $email }}</strong>.
        </p>

        @if(session('error') || isset($error))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
        {{ session('error') ?? $error }}
    </div>
@endif

        <form method="POST" action="{{ route('login.verify') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kod TAC</label>
                <input type="text" name="code" id="code" required maxlength="6"
                       class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                       placeholder="Contoh: 123456">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Sahkan & Masuk
            </button>
        </form>
    </div>

</body>
</html>
