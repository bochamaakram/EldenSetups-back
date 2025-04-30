<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeliveryItem;
use Illuminate\Http\Request;

class DeliveryItemController extends Controller
{
    public function index()
    {
        return response()->json(DeliveryItem::with('delivery')->get());
    }

    public function show($id)
    {
        return response()->json(DeliveryItem::with('delivery')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $deliveryItem = DeliveryItem::create($request->all());
        return response()->json($deliveryItem, 201);
    }

    public function update(Request $request, $id)
    {
        $deliveryItem = DeliveryItem::findOrFail($id);
        $deliveryItem->update($request->all());
        return response()->json($deliveryItem);
    }

    public function destroy($id)
    {
        DeliveryItem::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}