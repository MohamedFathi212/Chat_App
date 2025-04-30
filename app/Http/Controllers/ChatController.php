<?php


namespace App\Http\Controllers;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Events\MessageSent;
use App\Models\User;

class ChatController extends Controller
{
    public function fetchMessages(Request $request){
        $userId = session('LoggedUserInfo');
        $reciverId = $request->query('receiver_id');
        $messages = Chat::where(function($query) use ($userId, $reciverId){
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $reciverId);
        })->orWhere(function($query) use ($userId, $reciverId){
            $query->where('sender_id', $reciverId)
                  ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();
        return response()->json( 'messages',$messages);
    }

    public function sendMessage(Request $request){
        $userId = session('LoggedUserInfo');
        $reciverId = $request->query('receiver_id');

        $message = $request->message;

        $chat = Chat::create([
            'sender_id' => $userId,
            'receiver_id' => $reciverId,
            'message' => $message,
            'Sender_type' => 'user',
        ]);
        event(new MessageSent($message, $userId, $reciverId, $user->name, $user->picture));
        return response()->json(['success'=>true, 'message'=> 'Message Send Successfully. ']);
    }

}
