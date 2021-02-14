<?php

namespace App\ExternalApis;

interface WeatherForecastApi {
    /**
     * APIから天気予報データを取得
     *
     * @param float $latitude 緯度
     * @param float $longitude 経度
     *
     * @return string APIから取得したJSON
     */
    public function fetch(float $latitude, float $longitude): string;

    /**
     * APIから取得したデータの必要な部分を取得
     *
     * @param string fetchで取得したJSONの文字列
     *
     * @return string 天気予報の文字
     */
    public function parse(string $json): string;
}
