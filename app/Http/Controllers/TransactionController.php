<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\TACCode;
use App\Mail\TransactionInvite;

class TransactionController extends Controller
{
    public function __construct()
    {
        if (in_array(request()->route()?->getName(), ['transaction.create', 'transaction.store'])) {
            if (!session('auth_email')) {
                redirect()->route('login')->send();
            }
        }
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'item' => 'required|string|max:255',
        'amount' => 'required|numeric|min:1',
        'role' => 'required|in:buyer,seller',
        'target_contact_email' => 'required|email|max:255',
    ]);

    $creator = session('auth_email');
    $target = $request->target_contact_email;

    if ($target === $creator) {
        return redirect()->back()->withInput()->withErrors([
            'target_contact_email' => 'Please enter the ' . ($request->role === 'seller' ? 'buyer' : 'seller') . '\'s email, not your own.',
        ]);
    }

    $amount = $request->amount;
    $fee = round($amount * 0.025, 2);

    // Tetapkan ID tetap untuk buyer dan seller (jika perlu disimpan, walaupun dummy)
    $buyerId = $request->role === 'buyer' ? 1 : 2;
    $sellerId = $request->role === 'seller' ? 1 : 2;

    // === Perubahan paling penting di sini ===
    $transaction = Transaction::create([
        'id' => Str::uuid(),
        'item' => $request->item,
        'amount' => $amount,
        'fee' => $fee,
        'buyer_id' => $buyerId,
        'seller_id' => $sellerId,
        'status' => 'pending',
        'meta' => [
            'creator' => ['type' => $request->role, 'value' => $creator],
            'target' => [
                'type' => $request->role === 'buyer' ? 'seller' : 'buyer',
                'value' => $target
            ],
        ],
    ]);

    $tac = rand(100000, 999999);

    TACCode::create([
        'transaction_id' => $transaction->id,
        'contact' => $target,
        'code' => $tac,
    ]);

    $link = url("/tx/{$transaction->id}?verify=" . urlencode($target));
    Mail::to($target)->send(new TransactionInvite($transaction, $tac, $link, $creator));

    // Simpan session supaya creator pun boleh view dan manage
    session(['verified:' . $transaction->id => $creator]);

    return view('transactions.link', [
        'transaction' => $transaction,
        'link' => $link,
        'user_contact' => $creator,
    ]);
}

    public function show(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $verify = $request->query('verify');
        $sessionKey = 'verified:' . $transaction->id;

        if (session($sessionKey)) {
            return view('transactions.view', [
                'transaction' => $transaction,
                'user_contact' => session($sessionKey),
            ]);
        }

        if ($verify) {
            return view('transactions.verify', [
                'transaction' => $transaction,
                'expected' => $verify,
            ]);
        }

        abort(403, 'Unauthorized access to this transaction.');
    }

    public function verify(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $contact = $request->input('actual');
        $code = $request->input('code');

        $valid = TACCode::where('transaction_id', $id)
            ->where('contact', $contact)
            ->where('code', $code)
            ->exists();

        if (!$valid) {
            return redirect()->back()->with('error', 'TAC code is incorrect.');
        }

        session(['verified:' . $transaction->id => $contact]);

        return redirect()->route('transaction.view', [
            'id' => $transaction->id,
            'verify' => $contact
        ]);
    }

    // ✅ Fungsi Pembayaran Guna Billplz Sandbox
    public function pay(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $userContact = session('auth_email') ?? session('verified:' . $transaction->id);

        $creator = $transaction->meta['creator'];
        $target = $transaction->meta['target'];

        $userIsBuyer = ($creator['type'] === 'buyer' && $creator['value'] === $userContact)
                    || ($creator['type'] === 'seller' && $target['value'] === $userContact);

        if (!$userIsBuyer) {
            abort(403, 'Unauthorized to make payment.');
        }

        // === Billplz Sandbox Settings ===
        $apiKey = '84d7c0d1-26ca-4ed2-ac69-a492baf47037'; // 👈 Ganti sini
        $collectionId = 'rjzaqwsa'; // 👈 Ganti sini

        $billData = [
            'collection_id' => $collectionId,
            'email' => $userContact,
            'name' => 'Buyer',
            'amount' => intval(($transaction->amount + $transaction->fee) * 100),
            'callback_url' => url('/'),
            'redirect_url' => route('transaction.view', [
                'id' => $transaction->id,
                'verify' => $userContact
            ]),
            'description' => 'Payment for ' . $transaction->item,
            'reference_1_label' => 'Transaction ID',
            'reference_1' => $transaction->id,
        ];

        $response = Http::withBasicAuth($apiKey, '')
            ->post('https://www.billplz-sandbox.com/api/v3/bills', $billData);

        if ($response->failed()) {
            return back()->with('error', 'Failed to initiate payment.');
        }

        $bill = $response->json();

        return redirect($bill['url']);
    }
}
