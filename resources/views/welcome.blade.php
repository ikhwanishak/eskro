<!DOCTYPE html>
<html>
<head>
    <title>Create Transaction - Eskro</title>
</head>
<body>
    <h2>Create Escrow Transaction</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('transaction.store') }}">
        @csrf
        <input type="text" name="item" placeholder="Item name" required><br>
        <input type="number" step="0.01" name="amount" placeholder="Amount (MYR)" required><br>
        <input type="text" name="buyer_id" placeholder="Buyer ID" required><br>
        <input type="text" name="seller_id" placeholder="Seller ID" required><br>
        <button type="submit">Create</button>
    </form>
</body>
</html>