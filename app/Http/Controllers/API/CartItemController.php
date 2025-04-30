<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return response()->json(CartItem::with(['cart', 'product'])->get());
    }

    public function show($id)
    {
        return response()->json(CartItem::with(['cart', 'product'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cartItem = CartItem::create($request->all());
        return response()->json($cartItem, 201);
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->update($request->all());
        return response()->json($cartItem);
    }

    public function destroy($id)
    {
        CartItem::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}