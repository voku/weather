<?php

declare(strict_types=1);

namespace voku\weather\provider;

use voku\weather\constants\UnitConst;
use voku\weather\constants\WeatherConst;
use voku\weather\WeatherCollection;
use voku\weather\WeatherDto;
use voku\weather\WeatherQueryDto;
use voku\weather\WeatherSourceDto;

/**
 * @immutable
 */
final class DummyProvider implements ProviderInterface
{
    /**
     * @return list<WeatherSourceDto>
     */
    public function getSources(): array
    {
        return  [
            new WeatherSourceDto(
                'Dummy Data',
            ),
        ];
    }


    public function getWeatherCurrent(WeatherQueryDto $query): WeatherDto
    {
        return new WeatherDto(
            UnitConst::UNIT_METRIC,
            [
                new WeatherSourceDto('Dummy'),
            ],
            48.137154,
            11.576124,
            11.5,
            "°C",
            3.8,
            59,
            1021.3,
            9,
            "km/h",
            180,
            0,
            "mm/h",
            100,
            null,
            WeatherConst::TYPE_HISTORICAL,
            10,
            WeatherConst::ICON_CLOUDY,
            0,
            "min"
        );
    }

    public function getWeatherForecast(WeatherQueryDto $query): WeatherDto
    {
        return new WeatherDto(
            UnitConst::UNIT_METRIC,
            [
                new WeatherSourceDto('Dummy'),
            ],
            48.137154,
            11.576124,
            11,
            "°C",
            3.8,
            59,
            1021.3,
            9,
            "km/h",
            180,
            0,
            "mm/h",
            100,
            null,
            WeatherConst::TYPE_HISTORICAL,
            10,
            WeatherConst::ICON_CLOUDY,
            0,
            "min"
        );
    }

    public function getWeatherForecastCollection(WeatherQueryDto $query): WeatherCollection
    {
        $weather = new WeatherDto(
            UnitConst::UNIT_METRIC,
            [
                new WeatherSourceDto('Dummy'),
            ],
            48.137154,
            11.576124,
            10.5,
            "°C",
            3.8,
            59,
            1021.3,
            9,
            "km/h",
            180,
            0,
            "mm/h",
            100,
            null,
            WeatherConst::TYPE_HISTORICAL,
            10,
            WeatherConst::ICON_CLOUDY,
            0,
            "min"
        );

        return (new WeatherCollection())->add($weather);
    }

    public function getWeatherHistorical(WeatherQueryDto $query): WeatherDto
    {
        return new WeatherDto(
            UnitConst::UNIT_METRIC,
            [
                new WeatherSourceDto('Dummy'),
            ],
            48.137154,
            11.576124,
            12.5,
            "°C",
            3.8,
            59,
            1021.3,
            9,
            "km/h",
            180,
            0,
            "mm/h",
            100,
            null,
            WeatherConst::TYPE_HISTORICAL,
            10,
            WeatherConst::ICON_CLOUDY,
            0,
            "min"
        );
    }

    public function getWeatherHistoricalCollection(WeatherQueryDto $query): WeatherCollection
    {
        $weather = new WeatherDto(
            UnitConst::UNIT_METRIC,
            [
                new WeatherSourceDto('Dummy'),
            ],
            48.137154,
            11.576124,
            13.5,
            "°C",
            3.8,
            59,
            1021.3,
            9,
            "km/h",
            180,
            0,
            "mm/h",
            100,
            null,
            WeatherConst::TYPE_HISTORICAL,
            10,
            WeatherConst::ICON_CLOUDY,
            0,
            "min"
        );

        return (new WeatherCollection())->add($weather);
    }
}
