<?php

namespace App\Services;

use App\Models\RecievedMessage;
use Illuminate\Support\Collection;

class RequestParser {
    private $content;

    public function __construct(string $content)
    {
        $this->content = json_decode($content);
    }

    public function getRecievedMessages(): Collection
    {
        $recievedMessages = new Collection();
        if (is_null($this->content) || !is_array($this->content->events)) {
            return $recievedMessages;
        }

        foreach ($this->content->events as $event) {
            if ($event->type == 'message') {
                $replyToken = $event->replyToken;
                $message = $event->message->text;
                if ($replyToken && $message) {
                    $recievedMessages->add(new RecievedMessage($replyToken, $message));
                }
            }
        }

        return $recievedMessages;
    }
}
