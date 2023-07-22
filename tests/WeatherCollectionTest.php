<?php

use PHPUnit\Framework\TestCase;
use voku\weather\provider\BrightskyHttpProvider;
use voku\weather\provider\DummyProvider;
use voku\weather\WeatherQueryDto;

/**
 * @internal
 */
final class WeatherCollectionTest extends TestCase
{
    public function testAdd(): void
    {
        $weatherCollection = new \voku\weather\WeatherCollection();

        $weather = (new DummyProvider())->getWeatherHistorical(
            new WeatherQueryDto(48.137154, 11.576124)
        );

        $weatherCollectionNew = $weatherCollection->add($weather);

        static::assertSame(0, count($weatherCollection));
        static::assertSame(1, count($weatherCollectionNew));
    }
}
