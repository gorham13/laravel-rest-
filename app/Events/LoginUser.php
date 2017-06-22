<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;
use GeoIP;

class LoginUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $geodata;


    public function __construct($email)
    {
        $this->user_id = User::where('email', $email)->first()->id;
        //GeoIPException
        GeoIP::setIp('70.42.74.161');

        $this->geodata = GeoIP::get();
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
