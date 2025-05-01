<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender_id;
    public $receiver_id;
    public $sender_name;
    public $sender_image;
    public $time;
    public function __construct($message, $sender_id, $receiver_id, $sender_name, $sender_image)
    {
        $this->message = $message;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->sender_name = $sender_name;
        $this->sender_image = $sender_image ?? 'default-avatar.jpg'; 
        $this->time = now()->timezone('Africa/Cairo')->format('h:i A');
    }
    public function broadcastOn()
    {
        return new Channel('chat.' . $this->receiver_id); 
    }
     public function broadcastAs()
    {
        return 'seller-buyer-message';  
    }
}