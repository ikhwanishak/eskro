<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 font-sans">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="max-w-2xl w-full bg-white border border-blue-200 rounded-2xl shadow-md p-6 space-y-6">

            {{-- Heading --}}
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-600">Transaction Details</h2>
                <span class="text-sm text-gray-500">ID: {{ $transaction->id }}</span>
            </div>

            {{-- Transaction info --}}
            <div class="space-y-2">
                <p><span class="font-semibold">Item:</span> {{ $transaction->item }}</p>
                <p><span class="font-semibold">Amount:</span> RM {{ number_format($transaction->amount, 2) }}</p>
                <p><span class="font-semibold">Escrow Fee (2.5%):</span> RM {{ number_format($transaction->fee, 2) }}</p>
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

            {{-- User contact info --}}
            <div class="bg-blue-50 p-4 rounded-xl">
                <p class="text-sm text-gray-600">You are verified as:</p>
                <p class="text-lg font-bold text-blue-700">{{ $user_contact }}</p>
            </div>

            {{-- Actions --}}
            @php
                $creator = $transaction->meta['creator'] ?? ['type' => null, 'value' => null];
                $target = $transaction->meta['target'] ?? ['type' => null, 'value' => null];

                // Ambil email user dari session TAC dahulu, fallback ke session auth biasa
                $loggedEmail = session('verified:' . $transaction->id) ?? session('auth_email');

                $userIsBuyer =
                    ($creator['type'] === 'buyer' && $creator['value'] === $loggedEmail) ||
                    ($target['type'] === 'buyer' && $target['value'] === $loggedEmail);
            @endphp

            <div class="pt-4 border-t border-gray-200">
                @if($userIsBuyer && $transaction->status === 'pending')
                    <form method="POST" action="{{ route('transaction.pay', ['id' => $transaction->id]) }}">
                        @csrf
                        <button class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">
                            Pay Now
                        </button>
                    </form>
                @else
                    <p class="text-center text-sm text-gray-500">No action required at this moment.</p>
                @endif
            </div>

        </div>
    </div>

</body>
</html>
