<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fetchMessage()
    {
        return Message::with('user')->get();
    }

    public function sendMessages(Request $request)
    {
        auth()->user()->messages()->create(['message'=>$request->message]);
        return response(['status'=>'Message sent successfully']);
    }
}
