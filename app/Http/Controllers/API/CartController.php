<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return response()->json(Cart::with('items.product')->get());
    }

    public function show($id)
    {
        return response()->json(Cart::with('items.product')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required_without:session_id|exists:users,id',
            'session_id' => 'required_without:user_id|string',
        ]);

        $cart = Cart::create($request->all());
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->all());
        return response()->json($cart);
    }

    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}