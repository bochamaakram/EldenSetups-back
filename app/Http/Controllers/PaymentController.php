<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::whereHas('order', function($query) {
            $query->where('user_id', Auth::id());
        })->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'transaction_id' => 'nullable|string'
        ]);

        $order = Order::findOrFail($validated['order_id']);
        
        $this->authorize('create', [Payment::class, $order]);

        // In a real app, you would integrate with a payment gateway here
        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => $validated['payment_method'],
            'status' => 'completed', // In real app, this would depend on gateway response
            'transaction_id' => $validated['transaction_id'] ?? null
        ]);

        // Update order status
        $order->status = 'completed';
        $order->save();

        return response()->json($payment, 201);
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return $payment;
    }
}