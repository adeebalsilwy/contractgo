<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Return the chat view or iframe content
        return view('chat.index');
    }

    public function favorites(Request $request)
    {
        // Return empty favorites response to prevent 500 error
        return response()->json([
            'count' => 0,
            'favorites' => '',
            'status' => 'success'
        ]);
    }

    public function getContacts(Request $request)
    {
        // Return empty contacts response
        return response()->json([
            'contacts' => '',
            'last_page' => 1,
            'status' => 'success'
        ]);
    }

    public function idInfo(Request $request)
    {
        // Return basic user info
        $user = Auth::user();
        return response()->json([
            'user_avatar' => $user ? ($user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')) : asset('storage/photos/no-image.jpg'),
            'fetch' => true,
            'status' => 'success'
        ]);
    }

    public function fetchMessages(Request $request)
    {
        // Return empty messages
        return response()->json([
            'messages' => '',
            'status' => 'success'
        ]);
    }

    public function updateContacts(Request $request)
    {
        // Return success response
        return response()->json([
            'contactItem' => '',
            'status' => 'success'
        ]);
    }

    public function search(Request $request)
    {
        // Return empty search results
        return response()->json([
            'records' => '',
            'last_page' => 1,
            'status' => 'success'
        ]);
    }

    public function shared(Request $request)
    {
        // Return empty shared photos
        return response()->json([
            'shared' => '',
            'status' => 'success'
        ]);
    }
}