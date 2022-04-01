<?php

namespace App\Providers;

use App\Events\ArticlePublishEvent;
use App\Events\Game\CreatePokerTable;
use App\Events\Game\FinishPlayerAction;
use App\Events\Game\StartPlayerAction;
use App\Events\Game\StartPokerRoundEvent;
use App\Listeners\ArticlePublishPusher;
use App\Listeners\Game\StartPokerRoundListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ArticlePublishEvent::class => [
            ArticlePublishPusher::class
        ],
        CreatePokerTable::class=>[StartPokerRoundListener::class],
        StartPokerRoundEvent::class=>[],
        StartPlayerAction::class=>[],
        FinishPlayerAction::class=>[]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
