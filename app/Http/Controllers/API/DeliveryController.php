<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        return response()->json(Delivery::with(['trackingHistory', 'items'])->get());
    }

    public function show($id)
    {
        return response()->json(Delivery::with(['trackingHistory', 'items'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
            'notes' => 'sometimes|string',
        ]);

        $delivery = Delivery::create($request->all());
        return response()->json($delivery, 201);
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->update($request->all());
        return response()->json($delivery);
    }

    public function destroy($id)
    {
        Delivery::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}