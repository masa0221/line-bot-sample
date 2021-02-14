<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Deliver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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
        $eventsObj = json_decode($request->getContent());
        $replyToken = '';
        $replyMessage = '';
        if (is_null($eventsObj) || is_null($eventsObj->events)) {
            return response()->json(['message' => 'received(no events)']);
        }

        foreach ($eventsObj->events as $event) {
            if ($event->type == 'message') {
                $replyToken = $event->replyToken;
                $message = $event->message;

                // 2. 受け取ったメッセージの内容から返信するメッセージを生成
                switch ($message->text) {
                    case '今日の天気は？':
                        // 天気APIを使って情報を取得してきたら正しい情報にできる
                        $replyMessage = 'は、晴れかな・・・（しらんけど）';
                        break;
                    case '元気？':
                        $replyMessage = 'はい、元気です。あなたは？';
                        break;
                    case '後ウマイヤ朝の最盛期王は？':
                        $replyMessage = 'アブド＝アッラフマーン３世';
                        break;
                    default:
                        if (strpos($message->text, '？') !== false) {
                            // 疑問符が含まれている場合(部分一致)
                            $replyMessage = '「今日の天気は？」という質問に答える事ができますよ！';
                        } else {
                            $replyMessage = 'すみません、よくわかりません';
                        }
                }
            }

            Log::debug('message.text: ' . $message->text);
            Log::debug('replyMessage: ' . $replyMessage);
            Log::debug('replyToken: ' . $replyToken);

            // 3. 返信メッセージを返信先に送信
            if ($replyToken && $replyMessage) {
                $deliver = new Deliver(env('LINE_CHANNEL_ACCESS_TOKEN'), env('LINE_CHANNEL_SECRET'));
                $deliver->reply($replyToken, $replyMessage);
            }
        }

        return response()->json(['message' => 'received']);
    }
}

