<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Created</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center px-4">

    <div class="max-w-2xl w-full bg-white shadow-xl rounded-2xl p-6 space-y-6 border border-blue-200">

        {{-- Heading --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold text-blue-700">Transaction Created</h1>
            <p class="text-sm text-gray-500 mt-1">You can now share this transaction with the other party.</p>
        </div>

        {{-- Share Link --}}
        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-600">Transaction Link:</label>
            <div class="flex items-center space-x-2">
                <input type="text" readonly value="{{ $link }}" class="flex-1 px-3 py-2 border rounded-md text-sm text-gray-700 bg-gray-100" id="copyLinkInput">
                <button onclick="copyToClipboard()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">Copy</button>
            </div>
            <p class="text-xs text-gray-400">Send this link to the other party ({{ $transaction->meta['target']['value'] }})</p>
        </div>

        {{-- Transaction Details --}}
        <div class="pt-4 border-t">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Transaction Details</h2>
            <p><span class="font-semibold">Item:</span> {{ $transaction->item }}</p>
            <p><span class="font-semibold">Amount:</span> RM {{ number_format($transaction->amount, 2) }}</p>
            <p><span class="font-semibold">Escrow Fee:</span> RM {{ number_format($transaction->fee, 2) }}</p>
            <p><span class="font-semibold">Status:</span>
                @if($transaction->status === 'pending')
                    <span class="text-yellow-600 font-semibold">Pending Payment</span>
                @elseif($transaction->status === 'paid')
                    <span class="text-green-600 font-semibold">Paid</span>
                @else
                    {{ ucfirst($transaction->status) }}
                @endif
            </p>
        </div>

        {{-- Logged In User --}}
        <div class="bg-blue-50 p-4 rounded-xl mt-2">
            <p class="text-sm text-gray-600">You are logged in as:</p>
            <p class="text-lg font-bold text-blue-700">{{ session('auth_email') }}</p>
        </div>

        {{-- Action --}}
        @php
            $creator = $transaction->meta['creator'];
            $isCreatorBuyer = $creator['type'] === 'buyer' && $creator['value'] === session('auth_email');
        @endphp

        @if($isCreatorBuyer && $transaction->status === 'pending')
            <form method="POST" action="{{ route('transaction.pay', ['id' => $transaction->id]) }}" class="pt-4 border-t">
                @csrf
                <button class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">
                    Pay Now
                </button>
            </form>
        @else
            <p class="text-center text-sm text-gray-500 pt-4 border-t">No action required from your side.</p>
        @endif
    </div>

    <script>
        function copyToClipboard() {
            const input = document.getElementById("copyLinkInput");
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Link copied to clipboard!");
        }
    </script>
</body>
</html>
