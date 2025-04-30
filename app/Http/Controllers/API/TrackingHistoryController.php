<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;

class TrackingHistoryController extends Controller
{
    public function index()
    {
        return response()->json(TrackingHistory::with('delivery')->get());
    }

    public function show($id)
    {
        return response()->json(TrackingHistory::with('delivery')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'status' => 'required|string',
            'location' => 'required|string',
            'timestamp' => 'required|date',
        ]);

        $tracking = TrackingHistory::create($request->all());
        return response()->json($tracking, 201);
    }

    public function update(Request $request, $id)
    {
        $tracking = TrackingHistory::findOrFail($id);
        $tracking->update($request->all());
        return response()->json($tracking);
    }

    public function destroy($id)
    {
        TrackingHistory::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}