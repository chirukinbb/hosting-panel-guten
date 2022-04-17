<?php

namespace App\Listeners;

use App\Events\ArticlePublishEvent;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class ArticleFacebookAnnounce
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
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookSDKException $exception) {
            throw new \Exception('Some trouble with FB posting!'.PHP_EOL.$exception->getMessage());
        }

        if (isset($accessToken)) {
            $fb->setDefaultAccessToken($accessToken);
            $data = [

                'link' => \route('article',['article'=>$event->article->slug]),
                'name' => $event->article->title,
                'caption' => $event->article->title,
                'picture' => asset($event->article->thumbnail_path)
            ];
            $fb->post('/'.env('FACEBOOK_PAGE_ID').'/feed',$data);
        }
    }
}
