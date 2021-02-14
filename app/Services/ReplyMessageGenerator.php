<?php

namespace App\Services;

class ReplyMessageGenerator {
    public function generate($text)
    {
        switch ($text) {
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
                if (strpos($text, '？') !== false) {
                    // 疑問符が含まれている場合(部分一致)
                    $replyMessage = '「今日の天気は？」という質問に答える事ができますよ！';
                } else {
                    $replyMessage = 'すみません、よくわかりません';
                }
        }

        return $replyMessage;
    }
}
