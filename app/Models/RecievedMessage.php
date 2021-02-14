<?php

namespace App\Models;

class RecievedMessage {
    private $replyToken = '';
    private $text = '';

    public function __construct(string $replyToken, string $text)
    {
        $this->replyToken = $replyToken;
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getReplyToken()
    {
        return $this->replyToken;
    }
}
