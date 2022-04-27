<?php

namespace App\Services;

use App\ExternalApis\WeatherForecastApi;

class WeatherForecaster {
    private $weatherForecastApi;
    private $latitude;
    private $longitude;

    /**
     * @param WeatherForecastApi 天気予報API
     * @param float $latitude 緯度
     * @param float $longitude 経度
     */
    public function __construct(WeatherForecastApi $weatherForecastApi, $latitude, $longitude)
    {
        $this->weatherForecastApi = $weatherForecastApi;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * 指定された緯度・経度から天気予報を取得
     *
     * @return string 天気予報の文字
     */
    public function forecast()
    {
        $weatherJson = $this->weatherForecastApi->fetch(
            $this->latitude,
            $this->longitude
        );

        return $this->weatherForecastApi->parse($weatherJson);
    }
}
