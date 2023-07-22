<?php

use PHPUnit\Framework\TestCase;
use voku\weather\provider\DummyProvider;
use voku\weather\WeatherQueryDto;

/**
 * @internal
 */
final class WeatherDtoTest extends TestCase
{
    public function testCreateFromJson(): void
    {
        $weather = (new DummyProvider())->getWeatherHistorical(
            new WeatherQueryDto(48.137154, 11.576124)
        );

        static::assertEquals($weather, \voku\weather\WeatherDto::createFromJson(json_encode($weather)));
    }
}
