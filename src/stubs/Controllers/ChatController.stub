<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ChatController extends Controller
{
    use ValidatesRequests;

    /**
     * Description
     * @method __construct
     *
     * @return   void
     */
    public function __construct()
    {
        $this->middleware('api:user', ['only' => ['store']]);
    }

    /**
     * Get a listing of the resource
     * @method index
     *
     * @return   void
     */
    public function index()
    {
        return Chat::with('user')->latest()->limit(25)->get();
    }

    /**
     * Store a new Chat message
     * @method store
     *
     * @return   void
     */
    public function store()
    {
        $this->validate( request(), [
            'message' => 'required|min:1'
        ]);

        $message = auth()->user()->chats()->create([
            'message' => request('message')
        ]);

        return response($message, 201);
    }
}
