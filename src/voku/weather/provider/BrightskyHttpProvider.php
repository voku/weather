<?php

declare(strict_types=1);

namespace voku\weather\provider;

use voku\weather\constants\UnitConst;
use voku\weather\constants\WeatherConst;
use voku\weather\exception\ServerException;
use voku\weather\UnitHelper;
use voku\weather\WeatherCollection;
use voku\weather\WeatherDto;
use voku\weather\WeatherQueryDto;
use voku\weather\WeatherSourceDto;

/**
 * @immutable
 */
final class BrightskyHttpProvider extends AbstractHttpProvider
{
    /**
     * @param array<string, mixed> $rawData
     *
     * @throws ServerException
     *
     * @phpstan-param WeatherConst::TYPE_* $type
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    protected function mapRawData(
        float $latitude,
        float $longitude,
        array $rawData,
        string $type,
        string $unit
    ): WeatherDto|WeatherCollection {
        if (!\array_key_exists('weather', $rawData)) {
            throw new ServerException('no weather data found in: ' . print_r($rawData, true));
        }

        if ($type !== WeatherConst::TYPE_CURRENT) {
            $weatherCollection = new WeatherCollection();
            foreach ($rawData['weather'] as $weatherRawData) {
                $weatherCollection = $weatherCollection->add(
                    $this->mapItemRawData(
                        $latitude,
                        $longitude,
                        $weatherRawData,
                        $rawData['sources'],
                        null,
                        $unit
                    ),
                    false
                );
            }
            $weatherCollection = $weatherCollection->sortWeatherData();
        } else {
            return $this->mapItemRawData(
                $latitude,
                $longitude,
                $rawData['weather'],
                $rawData['sources'],
                $type,
                $unit
            );
        }

        return $weatherCollection;
    }

    /**
     * @param array<string, mixed> $weatherRawData
     * @param array<int, mixed> $sourcesRawData
     *
     * @phpstan-param WeatherConst::TYPE_* $type
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private function mapItemRawData(
        float $latitude,
        float $longitude,
        array $weatherRawData,
        array $sourcesRawData,
        ?string $type = null,
        string $unit = UnitConst::UNIT_METRIC
    ): WeatherDto {
        $utcDateTime = (new \DateTimeImmutable())->setTimezone(new \DateTimeZone('UTC'));
        $utcDateTime = $utcDateTime->setTimestamp(strtotime($weatherRawData['timestamp']));

        // auto-detect "type" for collections
        if (!$type) {
            $now = (new \DateTimeImmutable())->setTimezone(new \DateTimeZone('UTC'));
            if ($utcDateTime > $now) {
                $type = WeatherConst::TYPE_FORECAST;
            } else {
                $type = WeatherConst::TYPE_HISTORICAL;
            }
        }

        if (isset($weatherRawData['temperature'])) {
            $temperature = UnitHelper::mapTemperature($weatherRawData['temperature'], UnitConst::TEMPERATURE_CELSIUS, $unit);
        } else {
            $temperature = null;
        }

        if (isset($weatherRawData['dew_point'])) {
            $dewPoint = UnitHelper::mapTemperature($weatherRawData['dew_point'], UnitConst::TEMPERATURE_CELSIUS, $unit);
        } else {
            $dewPoint = null;
        }

        $humidity = $weatherRawData['relative_humidity'];

        if (isset($weatherRawData['pressure_msl'])) {
            $pressure = UnitHelper::mapPressure($weatherRawData['pressure_msl'], UnitConst::PRESSURE_HPA, $unit);
        } else {
            $pressure = null;
        }

        if (isset($weatherRawData['wind_speed'])) {
            $windSpeed = UnitHelper::mapSpeed($weatherRawData['wind_speed'], UnitConst::SPEED_KMH, $unit);
        } elseif (isset($weatherRawData['wind_speed_10'])) {
            $windSpeed = UnitHelper::mapSpeed($weatherRawData['wind_speed_10'], UnitConst::SPEED_KMH, $unit);
        } elseif (isset($weatherRawData['wind_speed_30'])) {
            $windSpeed = UnitHelper::mapSpeed($weatherRawData['wind_speed_30'], UnitConst::SPEED_KMH, $unit);
        } elseif (isset($weatherRawData['wind_speed_60'])) {
            $windSpeed = UnitHelper::mapSpeed($weatherRawData['wind_speed_60'], UnitConst::SPEED_KMH, $unit);
        } else {
            $windSpeed = null;
        }

        if (isset($weatherRawData['wind_direction'])) {
            $windDirection = $weatherRawData['wind_direction'];
        } elseif (isset($weatherRawData['wind_direction_10'])) {
            $windDirection = $weatherRawData['wind_direction_10'];
        } elseif (isset($weatherRawData['wind_direction_30'])) {
            $windDirection = $weatherRawData['wind_direction_30'];
        } elseif (isset($weatherRawData['wind_direction_60'])) {
            $windDirection = $weatherRawData['wind_direction_60'];
        } else {
            $windDirection = null;
        }

        if (isset($weatherRawData['precipitation'])) {
            $precipitation = UnitHelper::mapPrecipitation($weatherRawData['precipitation'], UnitConst::PRECIPITATION_MM, $unit);
        } elseif (isset($weatherRawData['precipitation_10'])) {
            $precipitation = UnitHelper::mapPrecipitation($weatherRawData['precipitation_10'], UnitConst::PRECIPITATION_MM, $unit);
        } elseif (isset($weatherRawData['precipitation_30'])) {
            $precipitation = UnitHelper::mapPrecipitation($weatherRawData['precipitation_30'], UnitConst::PRECIPITATION_MM, $unit);
        } elseif (isset($weatherRawData['precipitation_60'])) {
            $precipitation = UnitHelper::mapPrecipitation($weatherRawData['precipitation_60'], UnitConst::PRECIPITATION_MM, $unit);
        } else {
            $precipitation = null;
        }

        if (isset($weatherRawData['sunshine'])) {
            $sunshine = UnitHelper::mapSunshine((int)$weatherRawData['sunshine'], UnitConst::SUNSHINE_MIN, $unit);
        } elseif (isset($weatherRawData['sunshine_10'])) {
            $sunshine = UnitHelper::mapSunshine((int)$weatherRawData['sunshine_10'], UnitConst::SUNSHINE_MIN, $unit);
        } elseif (isset($weatherRawData['sunshine_30'])) {
            $sunshine = UnitHelper::mapSunshine((int)$weatherRawData['sunshine_30'], UnitConst::SUNSHINE_MIN, $unit);
        } elseif (isset($weatherRawData['sunshine_60'])) {
            $sunshine = UnitHelper::mapSunshine((int)$weatherRawData['sunshine_60'], UnitConst::SUNSHINE_MIN, $unit);
        } else {
            $sunshine = null;
        }

        $cloudCover = $weatherRawData['cloud_cover'];

        $weatherCode = $this->mapWeatherCode($weatherRawData);

        $icon = $this->mapIcon($weatherRawData);

        $temperatureUnit = UnitHelper::getTemperatureUnit($unit);

        $windSpeedUnit = UnitHelper::getSpeedUnit($unit);

        $precipitationUnit = UnitHelper::getPrecipitationUnit($unit);

        $sunshineUnit = UnitHelper::getSunshineUnit($unit);

        if ($sourcesRawData[0]['station_name'] ?? null) {
            $extraSource = [new WeatherSourceDto($sourcesRawData[0]['station_name'])];
        } else {
            $extraSource = [];
        }

        return new WeatherDto(
            $unit,
            array_merge($this->getSources(), $extraSource),
            $latitude,
            $longitude,
            $temperature,
            $temperatureUnit,
            $dewPoint,
            $humidity,
            $pressure,
            $windSpeed,
            $windSpeedUnit,
            $windDirection,
            $precipitation,
            $precipitationUnit,
            $cloudCover,
            $utcDateTime,
            $type,
            $weatherCode,
            $icon,
            $sunshine,
            $sunshineUnit
        );
    }

    /**
     * @return list<WeatherSourceDto>
     */
    public function getSources(): array
    {
        return  [
            new WeatherSourceDto(
                'Bright Sky',
                'https://brightsky.dev/',
            ),
            new WeatherSourceDto(
                'Deutscher Wetterdienst (dwd)',
                'https://www.dwd.de/',
            ),
        ];
    }

    /**
     * @param array<string, mixed> $weatherRawData
     *
     * @phpstan-return WeatherConst::CODE_*|null
     */
    private function mapWeatherCode(array $weatherRawData): ?int
    {
        $condition = $weatherRawData['condition'];

        return match ($condition) {
            'dry'          => WeatherConst::CODE_DRY,
            'fog'          => WeatherConst::CODE_FOG,
            'rain'         => WeatherConst::CODE_RAIN,
            'sleet'        => WeatherConst::CODE_SNOW_RAIN,
            'snow'         => WeatherConst::CODE_SNOW,
            'hail'         => WeatherConst::CODE_HAIL,
            'thunderstorm' => WeatherConst::CODE_THUNDERSTORM,
            default        => null,
        };
    }

    /**
     * @param array<string, mixed> $weatherRawData
     *
     * @phpstan-return WeatherConst::ICON_*|null
     */
    private function mapIcon(array $weatherRawData): ?string
    {
        $icon = $weatherRawData['icon'];

        return match ($icon) {
            'clear-day'           => WeatherConst::ICON_CLEAR_DAY,
            'clear-night'         => WeatherConst::ICON_CLEAR_NIGHT,
            'partly-cloudy-day'   => WeatherConst::ICON_CLOUDY_DAY,
            'partly-cloudy-night' => WeatherConst::ICON_CLOUDY_NIGHT,
            'cloudy'              => WeatherConst::ICON_CLOUDY,
            'rain'                => WeatherConst::ICON_RAIN,
            'fog'                 => WeatherConst::ICON_FOG,
            'snow'                => WeatherConst::ICON_SNOW,
            'thunderstorm'        => WeatherConst::ICON_THUNDERSTORM,
            'sleet'               => WeatherConst::ICON_SNOW_RAIN,
            'hail'                => WeatherConst::ICON_HAIL,
            'wind'                => WeatherConst::ICON_WIND,
            default               => null,
        };
    }

    protected function getWeatherCurrentUrl(WeatherQueryDto $query): string
    {
        return sprintf('https://api.brightsky.dev/current_weather?%s', http_build_query($this->getBaseQueryArgs($query)));
    }

    /**
     * @param WeatherQueryDto $query
     *
     * @return array{
     *     lat: float|null,
     *     lon: float|null,
     *     units: string
     * }
     */
    private function getBaseQueryArgs(WeatherQueryDto $query): array
    {
        return [
            'lat'   => $query->latitude,
            'lon'   => $query->longitude,
            'units' => 'dwd',
        ];
    }

    protected function getWeatherHistoricalUrl(WeatherQueryDto $query): string
    {
        $queryArray = $this->getBaseQueryArgs($query);

        $date = $query->dateTime ?? new \DateTimeImmutable();
        $queryArray['date'] = $date->format('c');

        $lastDateTime = $query->lastDateTime ?: \DateTimeImmutable::createFromInterface($date)->add(new \DateInterval('PT2H'));
        $queryArray['last_date'] = $lastDateTime->format('c');

        return sprintf('https://api.brightsky.dev/weather?%s', http_build_query($queryArray));
    }

    protected function getWeatherForecastUrl(WeatherQueryDto $query): string
    {
        $queryArray = $this->getBaseQueryArgs($query);

        $date = $query->dateTime ?? new \DateTimeImmutable();
        $queryArray['date'] = $date->format('c');

        $lastDateTime = $query->lastDateTime ?: \DateTimeImmutable::createFromInterface($date)->add(new \DateInterval('PT2H'));
        $queryArray['last_date'] = $lastDateTime->format('c');

        return sprintf('https://api.brightsky.dev/weather?%s', http_build_query($queryArray));
    }

    protected function getWeatherHistoricalCollectionUrl(WeatherQueryDto $query): string
    {
        $queryArray = $this->getBaseQueryArgs($query);

        $dateTime = $query->dateTime ?? new \DateTimeImmutable();
        $queryArray['date'] = $dateTime->format('c');
        if ($query->lastDateTime) {
            $queryArray['last_date'] = $query->lastDateTime->format('c');
        } else {
            $queryArray['last_date'] = (new \DateTimeImmutable($dateTime->format('Y-m-d 23:59:59')))->format('c');
        }

        return sprintf('https://api.brightsky.dev/weather?%s', http_build_query($queryArray));
    }

    protected function getWeatherForecastCollectionUrl(WeatherQueryDto $query): string
    {
        $queryArray = $this->getBaseQueryArgs($query);

        $dateTime = $query->dateTime ?? new \DateTimeImmutable();
        $queryArray['date'] = $dateTime->format('c');
        if ($query->lastDateTime) {
            $queryArray['last_date'] = $query->lastDateTime->format('c');
        } else {
            $queryArray['last_date'] = (new \DateTimeImmutable($dateTime->format('Y-m-d 23:59:59')))->format('c');
        }

        return sprintf('https://api.brightsky.dev/weather?%s', http_build_query($queryArray));
    }
}
