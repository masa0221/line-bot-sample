<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Deliver;
use App\Services\ReplyMessageGenerator;
use App\Services\RequestParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineController extends Controller
{
    // メッセージ送信
    public function delivery()
    {
        // 1. 登録されている友だちにメッセージを送信
        $deliver = new Deliver(env('LINE_CHANNEL_ACCESS_TOKEN'), env('LINE_CHANNEL_SECRET'));
        $deliver->deliveryAll('Hello LINE!');

        return response()->json(['message' => 'sent']);
    }

    // メッセージを受け取って返信
    public function callback(Request $request)
    {
        // 1. 受け取った情報からメッセージの情報を取り出す
        Log::debug('RequestBody: ' . $request->getContent());
        $parser = new RequestParser($request->getContent());
        $recievedMessages = $parser->getRecievedMessages();

        if ($recievedMessages->isEmpty()) {
            return response()->json(['message' => 'received(no events)']);
        }

        foreach ($recievedMessages as $recievedMessage) {
            // 2. 受け取ったメッセージの内容から返信するメッセージを生成
            $replyMessageGenerator = new ReplyMessageGenerator();
            $replyMessage = $replyMessageGenerator->generate($recievedMessage->getText());

            Log::debug('message.text: ' .$recievedMessage->getText());
            Log::debug('replyMessage: ' . $replyMessage);
            Log::debug('replyToken: ' . $recievedMessage->getReplyToken());

            // 3. 返信メッセージを返信先に送信
            $deliver = new Deliver(env('LINE_CHANNEL_ACCESS_TOKEN'), env('LINE_CHANNEL_SECRET'));
            $deliver->reply($recievedMessage->getReplyToken(), $replyMessage);
        }

        return response()->json(['message' => 'received']);
    }
}
