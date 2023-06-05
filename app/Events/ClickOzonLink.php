<?php

namespace App\Events;

use GuzzleHttp\Psr7\Request;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClickOzonLink
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offerId;
    public $request;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($offerId, $request)
    {
        $this->offerId = $offerId;
        $this->request = $request;
        /**
         * внести информацию в базу данных, доделать
         */
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
