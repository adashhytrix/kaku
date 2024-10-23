<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Yantrana\Components\User\Models\User;
use App\Yantrana\Components\Messenger\Models\ChatModel;
class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($userId, $message)
    {
        
        $userId;  // Store the ID
        $this->message = $message;
    }
 

    public function broadcastOn()
    {
      
        return new PrivateChannel('chat.');  // Use the user ID directly
    }




}
