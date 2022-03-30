<?php

namespace App\Listeners\Game;

use App\Events\Game\FindTableEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewRoundListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(FindTableEvent $event)
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
