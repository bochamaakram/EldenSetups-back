<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items.product')->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total and check product availability
        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            if (!$product->is_available || $product->quantity < $item['quantity']) {
                return response()->json([
                    'message' => "Product {$product->name} is not available in the requested quantity"
                ], 400);
            }

            $total += $product->price * $item['quantity'];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price
            ];
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
            
            // Update product quantity
            $product = Product::find($item['product_id']);
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        return response()->json($order->load('items.product'), 201);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return $order->load('items.product');
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);
        return response()->json($order->load('items.product'));
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        
        // Restore product quantities if order is cancelled
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->quantity += $item->quantity;
                $product->save();
            }
        }

        $order->delete();
        return response()->json(null, 204);
    }
}