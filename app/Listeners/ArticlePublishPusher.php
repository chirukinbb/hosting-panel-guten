<?php

namespace App\Listeners;

use App\Events\ArticlePublishEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Pusher\Pusher;

class ArticlePublishPusher implements ShouldBroadcast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(public ArticlePublishEvent $event)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        \Broadcast::channel('article-publish-channel',function () {});
    }

    public function broadcastOn()
    {
        return new Channel('article-publish-channel');
    }
}
