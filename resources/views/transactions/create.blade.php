<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Transaction</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Create a New Transaction</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transaction.store') }}" class="space-y-5">
            @csrf

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Role</label>
                <div class="flex space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="role" value="buyer" required class="accent-blue-600" onchange="updateEmailLabel()">
                        <span>Buyer</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="role" value="seller" required class="accent-blue-600" onchange="updateEmailLabel()">
                        <span>Seller</span>
                    </label>
                </div>
            </div>

            {{-- Item --}}
            <div>
                <label for="item" class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                <select name="item" id="item" required
                        class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none">
                    <option value="">-- Select Item --</option>
                    <option value="Event Ticket">Event Ticket</option>
                    <option value="Smartphone">Smartphone</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Shoes">Shoes</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Website Domain">Website Domain</option>
                    <option value="Social Media Account">Social Media Account</option>
                </select>
            </div>

            {{-- Amount --}}
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (MYR)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="1" required
                       class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                       placeholder="e.g. 250.00"
                       oninput="calculateFee()">
            </div>

            {{-- Fee --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Platform Fee (2.5%)</label>
                <input type="text" id="fee" disabled
                       class="w-full px-4 py-3 border bg-gray-100 rounded-lg text-gray-600">
            </div>

            {{-- Total with Fee --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total with Fee</label>
                <input type="text" id="total" disabled
                       class="w-full px-4 py-3 border bg-gray-100 rounded-lg text-gray-600">
            </div>

            {{-- Email Pihak Kedua --}}
            <div>
                <label id="emailLabel" for="target_contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email of the other person
                </label>
                <input type="email" name="target_contact_email" id="target_contact_email" required
                       class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                       placeholder="e.g. person@example.com">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Create Transaction
            </button>
        </form>
    </div>

    <script>
    function calculateFee() {
        const amount = parseFloat(document.getElementById('amount').value);
        const feeInput = document.getElementById('fee');
        const totalInput = document.getElementById('total');
        if (!isNaN(amount)) {
            const fee = (amount * 0.025);
            const total = amount + fee;
            feeInput.value = `RM ${fee.toFixed(2)}`;
            totalInput.value = `RM ${total.toFixed(2)}`;
        } else {
            feeInput.value = '';
            totalInput.value = '';
        }
    }

    function updateEmailLabel() {
        const role = document.querySelector('input[name="role"]:checked')?.value;
        const label = document.getElementById('emailLabel');
        if (role === 'buyer') {
            label.textContent = "Seller's Email (not your own)";
        } else if (role === 'seller') {
            label.textContent = "Buyer's Email (not your own)";
        } else {
            label.textContent = "Email of the other person";
        }
    }

    // 🔴 Prevent same email as creator (frontend validation)
    document.querySelector('form').addEventListener('submit', function(e) {
        const creatorEmail = "{{ session('auth_email') }}";
        const targetEmail = document.getElementById('target_contact_email').value.trim();
        if (targetEmail === creatorEmail) {
            alert("Please enter the other person's email, not your own.");
            e.preventDefault();
        }
    });
</script>

</body>
</html>
