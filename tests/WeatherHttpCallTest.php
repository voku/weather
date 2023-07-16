<?php

use PHPUnit\Framework\TestCase;
use voku\weather\constants\UnitConst;
use voku\weather\provider\BrightskyHttpProvider;
use voku\weather\WeatherQueryDto;

/**
 * @internal
 */
final class WeatherHttpCallTest extends TestCase
{
    public function testGetWeatherHistorical(): void
    {
        $latitude = 48.137154;
        $longitude = 11.576124;
        $dateTime = new \DateTimeImmutable('2023-01-01 12:00:00');

        $weatherQuery = new WeatherQueryDto(
            $latitude,
            $longitude,
            $dateTime
        );

        $weather = (new BrightskyHttpProvider())->getWeatherHistorical($weatherQuery);

        // DEBUG
        //var_dump($weather);

        static::assertSame(17.1, $weather->temperature);
    }

    public function testGetWeatherHistoricalCollection(): void
    {
        $latitude = 48.137154;
        $longitude = 11.576124;

        $weatherQuery = new WeatherQueryDto(
            $latitude,
            $longitude,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-01 12:00:00')
        );

        $weatherCollection = (new BrightskyHttpProvider())->getWeatherHistoricalCollection($weatherQuery);

        static::assertCount(25, $weatherCollection->getAll());
        static::assertCount(25, $weatherCollection->getHistorical());

        static::assertSame(11.5, $weatherCollection->getAll()[0]->temperature);
    }

    public function testGetWeatherHistoricalCollectionImperialUnits(): void
    {
        $latitude = 48.137154;
        $longitude = 11.576124;

        $weatherQuery = new WeatherQueryDto(
            $latitude,
            $longitude,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            new \DateTimeImmutable('2023-01-01 12:00:00'),
            UnitConst::UNIT_IMPERIAL
        );

        $weatherCollection = (new BrightskyHttpProvider())->getWeatherHistoricalCollection($weatherQuery);

        static::assertCount(25, $weatherCollection->getAll());
        static::assertCount(25, $weatherCollection->getHistorical());

        static::assertSame('52.7 °F', $weatherCollection->getAll()[0]->getTemperatureWithUnit());
        static::assertSame('5.59 mph', $weatherCollection->getAll()[0]->getWindSpeedWithUnit());
    }
}
