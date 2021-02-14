<?php

namespace Tests\Unit\Services;

use App\Models\RecievedMessage;
use App\Services\RequestParser;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class RequestParserTest extends TestCase
{
    /**
     * 受け取ったリクエストからメッセージ情報を生成できる
     * 
     * @dataProvider requestDataProvider
     *
     * @return void
     */
    public function testGetRecievedMessages($expected, $content)
    {
        $parser = new RequestParser($content);

        // ※オブジェクトの比較の場合はassertSameを使えない
        $this->assertEquals($parser->getRecievedMessages(), $expected);
    }

    public function requestDataProvider()
    {
        return [
            '何も受信できなかった場合' => [
                'expected' => new Collection(),
                'content' => '',
            ],
            'メッセージ情報がある場合' => [
                'expected' => new Collection([
                    new RecievedMessage('b38e061600xxxxxxxxxxxxxxxxxxxxxx', '今日の天気は？'),
                    new RecievedMessage('d3efdfea30xxxxxxxxxxxxxxxxxxxxxx', '後ウマイヤ朝の最盛期王は？'),
                ]),
                'content' => '{"events":[{"type":"message","replyToken":"b38e061600xxxxxxxxxxxxxxxxxxxxxx","source":{"userId":"U0123456789xxxxxxxxxxxxxxxxxxxxxx","type":"user"},"timestamp":1613261905163,"mode":"active","message":{"type":"text","id":"13553668987962","text":"今日の天気は？"}},{"type":"message","replyToken":"d3efdfea30xxxxxxxxxxxxxxxxxxxxxx","source":{"userId":"U0123456789xxxxxxxxxxxxxxxxxxxxxx","type":"user"},"timestamp":1613261905163,"mode":"active","message":{"type":"text","id":"13553668987962","text":"後ウマイヤ朝の最盛期王は？"}}],"destination":"U0123456789xxxxxxxxxxxxxxxxxxxxxx"}',
            ],
            'メッセージ情報がない場合' => [
                'expected' => new Collection(),
                'content' => '{"events":[],"destination":"U0123456789xxxxxxxxxxxxxxxxxxxxxx"}',
            ],
        ];
    }
}
