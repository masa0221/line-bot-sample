<?php

namespace Tests\Unit\Services;

use App\Services\ReplyMessageGenerator;
use App\Services\WeatherForecaster;
use Mockery;
use PHPUnit\Framework\TestCase;

class ReplyMessageGeneratorTest extends TestCase
{
    /**
     * 返信メッセージを期待通りに生成できることを確認
     * 
     * @dataProvider textDataProvider
     *
     * @return void
     */
    public function testGenerator($expected, $text)
    {
        $replyMessageGenerator = new ReplyMessageGenerator();
        $this->assertSame($replyMessageGenerator->generate($text), $expected);
    }

    public function textDataProvider()
    {
        return [
            '今日の天気は？' => [
                'expected' => 'は、晴れかな・・・（しらんけど）',
                'text' => '今日の天気は？',
            ],
            '元気？' => [
                'expected' => 'はい、元気です。あなたは？',
                'text' => '元気？',
            ],
            '後ウマイヤ朝の最盛期王は？' => [
                'expected' => 'アブド＝アッラフマーン３世',
                'text' => '後ウマイヤ朝の最盛期王は？',
            ],
            '文章の最後に「？」があるとき' => [
                'expected' => '「今日の天気は？」という質問に答える事ができますよ！',
                'text' => 'ほげほげ？',
            ],
            '文章の途中に「？」があるとき' => [
                'expected' => '「今日の天気は？」という質問に答える事ができますよ！',
                'text' => 'ほげ？ほげ',
            ],
            'どのパターンにも一致しないとき' => [
                'expected' => 'すみません、よくわかりません',
                'text' => 'ほげほげ',
            ],
        ];
    }

    /**
     * 天気予報の設定があるときのテスト
     *
     * @dataProvider weatherForecastorDataProvider
     *
     * @return void
     */
    public function testGeneratorWithWeatherForecaster($expected, $apiResult)
    {
        // WeatherForecaster#forecast の挙動を変更するモックを作成
        /** @var MockInterface */
        $weatherForecasterMock = Mockery::mock(WeatherForecaster::class);
        $weatherForecasterMock->shouldReceive('forecast')->andReturn($apiResult);

        /** @var WeatherForecaster $weatherForecasterMock */
        $replyMessageGenerator = new ReplyMessageGenerator($weatherForecasterMock);

        $this->assertSame($replyMessageGenerator->generate('今日の天気は？'), $expected);
    }

    public function weatherForecastorDataProvider()
    {
        return [
            '天気予報を取得できたとき' => [
                'expected' => '晴れ',
                'apiResult' => '晴れ',
            ],
            '天気予報を取得できなかったとき' => [
                'expected' => '天気情報を取得できませんでした。懲りずにまた明日聞いてください(´ . .̫ . `)',
                'apiResult' => '',
            ],
        ];
    }
}
