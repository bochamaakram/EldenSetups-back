<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return CartItem::where('user_id', $user->id)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'name' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $cartItem = CartItem::create($data);

        return response()->json($cartItem, 201);
    }
}