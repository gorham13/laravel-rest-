<?php

namespace App\Listeners;

use App\Events\LoginUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LogRegister;

class PutLoginInfo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginUser  $event
     * @return void
     */
    public function handle(LoginUser $event)
    {
        $registerLog = new LogRegister();

        $registerLog->user_id = $event->user_id;
        $registerLog->city = $event->geodata['city'];
        $registerLog->country = $event->geodata['country'];
        $registerLog->region = $event->geodata['region'];

        $registerLog->save();

    }
}
