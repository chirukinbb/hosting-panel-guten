<?php

namespace App\Listeners;

use App\Events\ArticlePublishEvent;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class ArticleTelegramAnnounce
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
     * @param  object  $event
     * @return void
     */
    public function handle(ArticlePublishEvent $event)
    {
        if (env('TELEGRAM_CHANNEL_TOKEN')){
            $bot = new Api();

            if ($event->article->thumbnail_path) {
                $bot->sendPhoto([
                    'chat_id' => env('TELEGRAM_CHANNEL_TOKEN'),
                    'parse_mode' => 'HTML',
                    'caption'=>sprintf(
                        '<a href="%s"><b>%s</b></a>',
                        route('article', ['article'=>$event->article->slug]),
                        $event->article->title,
                    ),
                    'photo'=>InputFile::create($event->article->thumbnail_path)
                ]);
            } else {
                $bot->sendMessage([
                    'chat_id' => env('TELEGRAM_CHANNEL_TOKEN'),
                    'text' => sprintf(
                        '<a href="%s"><b>%s</b></a> %s',
                        route('article', ['article'=>$event->article->slug]),
                        $event->article->title,
                        strip_tags(Str::substr($event->article->content,0,150).'...')
                    ),
                    'parse_mode' => 'HTML'
                ]);
            }
        }
    }
}
