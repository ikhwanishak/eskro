<h2>New Escrow Transaction Created</h2>

<p>Hello,</p>

<p>A new transaction has been created involving you:</p>

<ul>
    <li><strong>Item:</strong> {{ $transaction->item }}</li>
    <li><strong>Amount:</strong> RM {{ number_format($transaction->amount, 2) }}</li>
    <li><strong>Fee:</strong> RM {{ number_format($transaction->fee, 2) }}</li>
    <li><strong>Creator ({{ $transaction->meta['creator']['type'] }}):</strong> {{ $creator }}</li>
</ul>

<p><strong>Your One-Time Code (TAC):</strong> {{ $tac }}</p>

<p>Click the link below to access the transaction:</p>
<a href="{{ $link }}" style="display:inline-block;background-color:#2563eb;color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:bold;">
    View Transaction</a>

<p>Thank you,<br>Eskro Team</p>
