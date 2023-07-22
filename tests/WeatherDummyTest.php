<?php

use PHPUnit\Framework\TestCase;
use voku\weather\constants\UnitConst;
use voku\weather\provider\BrightskyHttpProvider;
use voku\weather\provider\DummyProvider;
use voku\weather\WeatherQueryDto;

/**
 * @internal
 */
final class WeatherDummyTest extends TestCase
{
    public function testGetWeatherCurrentDummy(): void
    {
        $weatherQuery = new WeatherQueryDto(48.137154, 11.576124);

        $weather = (new DummyProvider())->getWeatherCurrent($weatherQuery);

        $expected = 'voku\weather\WeatherDtoObject([unit]=>metric[sources]=>Array([0]=>voku\weather\WeatherSourceDtoObject([name]=>Dummy[url]=>))[latitude]=>48.137154[longitude]=>11.576124[temperature]=>11.5[temperatureUnit]=>°C[dewPoint]=>3.8[humidity]=>59[pressure]=>1021.3[windSpeed]=>9[windSpeedUnit]=>km/h[windDirection]=>180[precipitation]=>0[precipitationUnit]=>mm/h[cloudCover]=>100[utcDateTime]=>[type]=>historical[weatherCode]=>10[icon]=>cloudy[sunshine]=>0[sunshineUnit]=>min)';

        static::assertSame($expected, str_replace(' ', '', str_replace(["\n", "\r\n"], ' ', print_r($weather, true))));
    }

    public function testGetWeatherForecastDummy(): void
    {
        $weatherQuery = new WeatherQueryDto(48.137154, 11.576124);

        $weather = (new DummyProvider())->getWeatherForecast($weatherQuery);

        $expected = 'voku\weather\WeatherDtoObject([unit]=>metric[sources]=>Array([0]=>voku\weather\WeatherSourceDtoObject([name]=>Dummy[url]=>))[latitude]=>48.137154[longitude]=>11.576124[temperature]=>11[temperatureUnit]=>°C[dewPoint]=>3.8[humidity]=>59[pressure]=>1021.3[windSpeed]=>9[windSpeedUnit]=>km/h[windDirection]=>180[precipitation]=>0[precipitationUnit]=>mm/h[cloudCover]=>100[utcDateTime]=>[type]=>historical[weatherCode]=>10[icon]=>cloudy[sunshine]=>0[sunshineUnit]=>min)';

        static::assertSame($expected, str_replace(' ', '', str_replace(["\n", "\r\n"], ' ', print_r($weather, true))));
    }

    public function testGetWeatherForecastCollectionDummy(): void
    {
        $weatherQuery = new WeatherQueryDto(48.137154, 11.576124);

        $weather = (new DummyProvider())->getWeatherForecastCollection($weatherQuery);

        $expected = 'voku\weather\WeatherCollectionObject([current:voku\weather\WeatherCollection:private]=>[historical:voku\weather\WeatherCollection:private]=>Array([0]=>voku\weather\WeatherDtoObject([unit]=>metric[sources]=>Array([0]=>voku\weather\WeatherSourceDtoObject([name]=>Dummy[url]=>))[latitude]=>48.137154[longitude]=>11.576124[temperature]=>10.5[temperatureUnit]=>°C[dewPoint]=>3.8[humidity]=>59[pressure]=>1021.3[windSpeed]=>9[windSpeedUnit]=>km/h[windDirection]=>180[precipitation]=>0[precipitationUnit]=>mm/h[cloudCover]=>100[utcDateTime]=>[type]=>historical[weatherCode]=>10[icon]=>cloudy[sunshine]=>0[sunshineUnit]=>min))[forecast:voku\weather\WeatherCollection:private]=>Array())';

        static::assertSame($expected, str_replace(' ', '', str_replace(["\n", "\r\n"], ' ', print_r($weather, true))));
    }

    public function testGetWeatherHistoricalDummy(): void
    {
        $weatherQuery = new WeatherQueryDto(48.137154, 11.576124);

        $weather = (new DummyProvider())->getWeatherHistorical($weatherQuery);

        $expected = 'voku\weather\WeatherDtoObject([unit]=>metric[sources]=>Array([0]=>voku\weather\WeatherSourceDtoObject([name]=>Dummy[url]=>))[latitude]=>48.137154[longitude]=>11.576124[temperature]=>12.5[temperatureUnit]=>°C[dewPoint]=>3.8[humidity]=>59[pressure]=>1021.3[windSpeed]=>9[windSpeedUnit]=>km/h[windDirection]=>180[precipitation]=>0[precipitationUnit]=>mm/h[cloudCover]=>100[utcDateTime]=>[type]=>historical[weatherCode]=>10[icon]=>cloudy[sunshine]=>0[sunshineUnit]=>min)';

        static::assertSame($expected, str_replace(' ', '', str_replace(["\n", "\r\n"], ' ', print_r($weather, true))));
    }

    public function testGetWeatherHistoricalCollectionDummy(): void
    {
        $weatherQuery = new WeatherQueryDto(48.137154, 11.576124);

        $weather = (new DummyProvider())->getWeatherHistoricalCollection($weatherQuery);

        $expected = 'voku\weather\WeatherCollectionObject([current:voku\weather\WeatherCollection:private]=>[historical:voku\weather\WeatherCollection:private]=>Array([0]=>voku\weather\WeatherDtoObject([unit]=>metric[sources]=>Array([0]=>voku\weather\WeatherSourceDtoObject([name]=>Dummy[url]=>))[latitude]=>48.137154[longitude]=>11.576124[temperature]=>13.5[temperatureUnit]=>°C[dewPoint]=>3.8[humidity]=>59[pressure]=>1021.3[windSpeed]=>9[windSpeedUnit]=>km/h[windDirection]=>180[precipitation]=>0[precipitationUnit]=>mm/h[cloudCover]=>100[utcDateTime]=>[type]=>historical[weatherCode]=>10[icon]=>cloudy[sunshine]=>0[sunshineUnit]=>min))[forecast:voku\weather\WeatherCollection:private]=>Array())';

        static::assertSame($expected, str_replace(' ', '', str_replace(["\n", "\r\n"], ' ', print_r($weather, true))));
    }

}
