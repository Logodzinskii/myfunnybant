<?php

namespace App\Events;

use App\Models\OzonShopItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class UserSearch
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $search;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($search)
    {
        $this->search = $search;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
