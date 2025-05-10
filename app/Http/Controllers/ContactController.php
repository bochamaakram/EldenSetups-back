<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the contact messages.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Validate query parameters
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|string|max:255',
            'sort' => 'sometimes|string|in:name,email,created_at',
            'direction' => 'sometimes|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $perPage = $request->per_page ?? 15;
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';

        $query = ContactMessage::query();

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('message', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting
        $query->orderBy($sort, $direction);

        // Pagination
        $messages = $query->paginate($perPage);

        return response()->json([
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
            'links' => [
                'first' => $messages->url(1),
                'last' => $messages->url($messages->lastPage()),
                'prev' => $messages->previousPageUrl(),
                'next' => $messages->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created contact message.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $message = ContactMessage::create($request->all());

        return response()->json([
            'message' => 'Contact message created successfully',
            'data' => $message
        ], 201);
    }

    /**
     * Display the specified contact message.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'error' => 'Message not found'
            ], 404);
        }

        return response()->json([
            'data' => $message
        ]);
    }

    /**
     * Update the specified contact message.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'error' => 'Message not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'message' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $message->update($request->all());

        return response()->json([
            'message' => 'Contact message updated successfully',
            'data' => $message
        ]);
    }

    /**
     * Remove the specified contact message.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'error' => 'Message not found'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'message' => 'Contact message deleted successfully'
        ]);
    }

    /**
     * Mark a message as read/unread.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'error' => 'Message not found'
            ], 404);
        }

        $message->update([
            'is_read' => $request->status ?? true
        ]);

        return response()->json([
            'message' => 'Message status updated successfully',
            'data' => $message
        ]);
    }
}