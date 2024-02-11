<?php

use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use Illuminate\Http\Request;

class CustomChannelManager implements ChannelManager
{
    public function broadcastHttpMessage(Request $request)
    {
        // Получить параметр задержки из заголовка сообщения
        $delay = $request->header('Delay');

        // Установить таймер на нужное время
        $timer = setTimeout(function() use ($request) {
            // Транслировать сообщение через вебсокет
            $this->broadcast($request->input('channel'), $request->input('message'));
        }, $delay);
    }
}
