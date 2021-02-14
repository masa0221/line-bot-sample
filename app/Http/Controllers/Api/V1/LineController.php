<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Deliver;
use App\Services\ReplyMessageGenerator;
use App\Services\RequestParser;
use Illuminate\Http\Request;

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
        $parser = new RequestParser($request->getContent());
        $recievedMessages = $parser->getRecievedMessages();

        if ($recievedMessages->isEmpty()) {
            return response()->json(['message' => 'received(no events)']);
        }

        $replyMessageGenerator = new ReplyMessageGenerator();
        $deliver = new Deliver(env('LINE_CHANNEL_ACCESS_TOKEN'), env('LINE_CHANNEL_SECRET'));
        foreach ($recievedMessages as $recievedMessage) {
            // 2. 受け取ったメッセージの内容から返信するメッセージを生成
            $replyMessage = $replyMessageGenerator->generate($recievedMessage->getText());

            // 3. 返信メッセージを返信先に送信
            $deliver->reply($recievedMessage->getReplyToken(), $replyMessage);
        }

        return response()->json(['message' => 'received']);
    }
}
