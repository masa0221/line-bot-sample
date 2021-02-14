<?php

namespace App\Services;

class ReplyMessageGenerator {
    private $weatherForecaster;

    public function __construct(WeatherForecaster $weatherForecaster = null)
    {
        $this->weatherForecaster = $weatherForecaster;
    }

    public function generate($text)
    {
        switch ($text) {
            case '今日の天気は？':
                $replyMessage = $this->generateWeatherForecast();
                break;
            case '元気？':
                $replyMessage = 'はい、元気です。あなたは？';
                break;
            case '後ウマイヤ朝の最盛期王は？':
                $replyMessage = 'アブド＝アッラフマーン３世';
                break;
            default:
                if (strpos($text, '？') !== false) {
                    // 疑問符が含まれている場合(部分一致)
                    $replyMessage = '「今日の天気は？」という質問に答える事ができますよ！';
                } else {
                    $replyMessage = 'すみません、よくわかりません';
                }
        }

        return $replyMessage;
    }

    private function generateWeatherForecast()
    {
        if (!$this->weatherForecaster) {
            return 'は、晴れかな・・・（しらんけど）';
        }

        $replyMessage = $this->weatherForecaster->forecast();
        if (!$replyMessage) {
            $replyMessage = '天気情報を取得できませんでした。懲りずにまた明日聞いてください(´ . .̫ . `)';
        }

        return $replyMessage;
    }
}
