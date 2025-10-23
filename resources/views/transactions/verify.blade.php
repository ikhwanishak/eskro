<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Identity</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }
        .box {
            max-width: 480px;
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            margin: auto;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        p {
            margin-bottom: 20px;
            color: #555;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: #2ecc71;
        }
        button {
            background: #2ecc71;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .error {
            color: red;
            margin-top: 15px;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Verify Your Identity</h2>
        <p>Please enter your {{ $transaction->meta['target']['type'] ?? 'contact' }} and TAC code to access this transaction.</p>

        <form method="POST" action="{{ route('transaction.verify', $transaction->id) }}">
            @csrf
            <input type="hidden" name="expected" value="{{ $expected }}">

            <input
                type="{{ $transaction->meta['target']['type'] === 'email' ? 'email' : 'tel' }}"
                name="actual"
                placeholder="Enter your {{ $transaction->meta['target']['type'] }}"
                required
                value="{{ old('actual', '') }}"
            >

            <input
                type="text"
                name="code"
                placeholder="Enter TAC Code"
                required
                value="{{ old('code', '') }}"
            >

            <button type="submit">Verify</button>

            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif
        </form>
    </div>
</body>
</html>
