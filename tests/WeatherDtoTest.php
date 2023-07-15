<?php

use PHPUnit\Framework\TestCase;
use voku\weather\provider\BrightskyHttpProvider;
use voku\weather\WeatherQueryDto;

/**
 * @internal
 */
final class WeatherDtoTest extends TestCase
{
    public function testGetWeatherHistorical(): void
    {
        $latitude = 48.137154;
        $longitude = 11.576124;
        $dateTime = new DateTimeImmutable('2023-01-01 12:00:00');

        $weatherQuery = new WeatherQueryDto(
            $latitude,
            $longitude,
            $dateTime
        );

        $weather = (new BrightskyHttpProvider())->getWeatherHistorical($weatherQuery);

        static::assertEquals($weather, \voku\weather\WeatherDto::createFromJson(json_encode($weather)));
    }
}
