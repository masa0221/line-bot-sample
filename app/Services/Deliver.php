<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class Deliver {
    private $bot;

    public function __construct($accessToken, $secret)
    {
        $httpClient = new CurlHTTPClient($accessToken);
        $this->bot = new LINEBot($httpClient, ['channelSecret' => $secret]);
    }

    public function deliveryAll($message)
    {
        $textBuilder = new TextMessageBuilder($message);
        $response = $this->bot->broadcast($textBuilder);

        if (!$response->isSucceeded()) {
            Log::error($response->getRawBody());
        }
    }

    public function reply($replyToken, $message)
    {
        $textMessageBuilder = new TextMessageBuilder($message);
        $response = $this->bot->replyMessage($replyToken, $textMessageBuilder);

        if (!$response->isSucceeded()) {
            Log::error($response->getRawBody());
        }
    }
}

