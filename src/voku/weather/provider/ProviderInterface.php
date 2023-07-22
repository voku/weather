<?php

declare(strict_types=1);

namespace voku\weather\provider;

use voku\weather\WeatherCollection;
use voku\weather\WeatherDto;
use voku\weather\WeatherQueryDto;
use voku\weather\WeatherSourceDto;

interface ProviderInterface
{
    public function getWeatherCurrent(WeatherQueryDto $query): WeatherDto;

    public function getWeatherForecast(WeatherQueryDto $query): WeatherDto;

    public function getWeatherForecastCollection(WeatherQueryDto $query): WeatherCollection;

    /**
     * @param WeatherQueryDto $query
     *
     * @return WeatherDto
     */
    public function getWeatherHistorical(WeatherQueryDto $query): WeatherDto;

    /**
     * @param WeatherQueryDto $query
     *
     * @return WeatherCollection
     */
    public function getWeatherHistoricalCollection(WeatherQueryDto $query): WeatherCollection;

    /**
     * @return WeatherSourceDto[]
     */
    public function getSources(): array;
}
