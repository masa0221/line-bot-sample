<?php

namespace App\ExternalApis\WeatherForecastApi;

use App\ExternalApis\WeatherForecastApi;
use GuzzleHttp\Client;

class OpenWeatherMap implements WeatherForecastApi {
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * OpenWeatherMapから天気予報データを取得
     *
     * @param float $latitude 緯度
     * @param float $longitude 経度
     *
     * @return string OpenWeatherMapから取得したJSON
     *
     * @see https://openweathermap.org/api/one-call-api#current
     */
    public function fetch(float $latitude, float $longitude): string
    {
        $query_data = [
            'lat' => $latitude,
            'lon' => $longitude,
            'exclude' => 'current,minutely,hourly',
            'lang' => 'ja',
            'appid' => $this->apiKey,
        ];
        $url = 'https://api.openweathermap.org/data/2.5/onecall?'.http_build_query($query_data);

        $client = new Client();
        $response = $client->request('GET', $url);

        return $response->getBody();
    }

    /**
     * OpenWeatherMapから取得したデータの必要な部分を取得
     * (その日の天気予報のみ取得)
     *
     * @param string JSONの文字列
     *
     * @return string 天気予報の文字
     */
    public function parse($json): string
    {
        $weatherInfo = json_decode($json);
        if (!isset($weatherInfo->daily[0]->weather[0]->description)) {
            return '';
        }

        return $weatherInfo->daily[0]->weather[0]->description;
    }
}
