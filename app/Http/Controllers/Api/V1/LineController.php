<?php

namespace App\Http\Controllers\Api\V1;

use App\ExternalApis\WeatherForecastApi\OpenWeatherMap;
use App\Http\Controllers\Controller;
use App\Services\Deliver;
use App\Services\ReplyMessageGenerator;
use App\Services\RequestParser;
use App\Services\WeatherForecaster;
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

        $replyMessageGenerator = $this->makeReplyMessageGenerator();
        $deliver = new Deliver(env('LINE_CHANNEL_ACCESS_TOKEN'), env('LINE_CHANNEL_SECRET'));
        foreach ($recievedMessages as $recievedMessage) {
            // 2. 受け取ったメッセージの内容から返信するメッセージを生成
            $replyMessage = $replyMessageGenerator->generate($recievedMessage->getText());

            // 3. 返信メッセージを返信先に送信
            $deliver->reply($recievedMessage->getReplyToken(), $replyMessage);
        }

        return response()->json(['message' => 'received']);
    }

    public function makeReplyMessageGenerator()
    {
        // ここを入れ替えると別の天気予報APIを使うこともできる
        $openWeatherMap = new OpenWeatherMap(env('OPENWEATHERMAP_API_KEY'));

        // FIXME: メッセージを受け取った人ごとに変更
        // (今は環境変数に依存しているので固定の場所の天気しか取得できない)
        $weatherForcaster = new WeatherForecaster(
            $openWeatherMap,
            env('OPENWEATHERMAP_LATITUDE'),
            env('OPENWEATHERMAP_LONGITUDE')
        );

        return new ReplyMessageGenerator($weatherForcaster);
    }
}
