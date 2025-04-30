<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::with(['order', 'product'])->get());
    }

    public function show($id)
    {
        return response()->json(OrderItem::with(['order', 'product'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem = OrderItem::create($request->all());
        return response()->json($orderItem, 201);
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->update($request->all());
        return response()->json($orderItem);
    }

    public function destroy($id)
    {
        OrderItem::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}