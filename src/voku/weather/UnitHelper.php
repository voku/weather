<?php

declare(strict_types=1);

namespace voku\weather;

use voku\weather\constants\UnitConst;

final class UnitHelper
{
    private const KELVIN_ABSOLUTE_ZERO = 273.15;

    private const ONE_MI_IN_KM = 1.609344;

    private const ONE_MS_IN_KMH = 3.6;

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     *
     * @phpstan-return '°F'|'K'|'°C'
     */
    public static function getTemperatureUnit(string $unit): string
    {
        return match ($unit) {
            UnitConst::UNIT_IMPERIAL => '°F',
            UnitConst::UNIT_STANDARD => 'K',
            UnitConst::UNIT_METRIC  => '°C',
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     *
     * @phpstan-return 'km/h'|'mph'|'ms'
     */
    public static function getSpeedUnit(string $unit): string
    {
        return match ($unit) {
            UnitConst::UNIT_METRIC   => 'km/h',
            UnitConst::UNIT_IMPERIAL => 'mph',
            default                  => 'ms',
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     *
     * @phpstan-return 'inches/h'|'mm/h'
     */
    public static function getPrecipitationUnit(string $unit): string
    {
        return match ($unit) {
            UnitConst::UNIT_IMPERIAL => 'inches/h',
            default                  => 'mm/h',
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     *
     * @phpstan-return 'min'|'s'
     */
    public static function getSunshineUnit(string $unit): string
    {
        return match ($unit) {
            UnitConst::UNIT_IMPERIAL => 's',
            default                  => 'min',
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public static function mapTemperature(float $temperature, string $from, string $unit): float
    {
        return match ($from) {
            UnitConst::TEMPERATURE_KELVIN     => self::mapTemperatureFromKelvin($temperature, $unit),
            UnitConst::TEMPERATURE_FAHRENHEIT => self::mapTemperatureFromFahrenheit($temperature, $unit),
            default                           => self::mapTemperatureFromCelsius($temperature, $unit),
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapTemperatureFromKelvin(float $temperature, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_METRIC   => self::kelvinToCelsius($temperature),
            UnitConst::UNIT_IMPERIAL => self::celsiusToFahrenheit(self::kelvinToCelsius($temperature)),
            default                  => $temperature,
        };
    }

    public static function kelvinToCelsius(float $kelvin): float
    {
        return round($kelvin - self::KELVIN_ABSOLUTE_ZERO, 2);
    }

    public static function celsiusToFahrenheit(float $celsius): float
    {
        return round($celsius * (9 / 5) + 32, 2);
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapTemperatureFromFahrenheit(float $temperature, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_METRIC   => self::fahrenheitToCelsius($temperature),
            UnitConst::UNIT_IMPERIAL => self::celsiusToKelvin(self::fahrenheitToCelsius($temperature)),
            default                  => $temperature,
        };
    }

    public static function fahrenheitToCelsius(float $fahrenheit): float
    {
        return round(($fahrenheit - 32) * (5 / 9), 2);
    }

    public static function celsiusToKelvin(float $celsius): float
    {
        return round($celsius + self::KELVIN_ABSOLUTE_ZERO, 2);
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapTemperatureFromCelsius(float $temperature, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_IMPERIAL => self::celsiusToFahrenheit($temperature),
            UnitConst::UNIT_STANDARD => self::celsiusToKelvin($temperature),
            default                  => $temperature,
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public static function mapPressure(float $pressure, string $from, string $unit): float
    {
        return match ($from) {
            UnitConst::PRESSURE_PA => match ($unit) {
                UnitConst::UNIT_IMPERIAL, UnitConst::UNIT_METRIC => $pressure / 100,
                default => $pressure,
            },
            default => match ($unit) {
                UnitConst::UNIT_STANDARD => $pressure * 100,
                default                  => $pressure,
            },
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public static function mapSpeed(float $speed, string $from, string $unit): float
    {
        return match ($from) {
            UnitConst::SPEED_MS  => self::mapSpeedFromMs($speed, $unit),
            UnitConst::SPEED_MPH => self::mapSpeedFromMph($speed, $unit),
            default              => self::mapSpeedFromKmh($speed, $unit),
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapSpeedFromMs(float $speed, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_METRIC   => self::msToKmh($speed),
            UnitConst::UNIT_IMPERIAL => self::kmhToMph(self::msToKmh($speed)),
            default                  => $speed,
        };
    }

    public static function msToKmh(float $ms): float
    {
        return round($ms * self::ONE_MS_IN_KMH, 2);
    }

    public static function kmhToMph(float $kmh): float
    {
        return round($kmh / self::ONE_MI_IN_KM, 2);
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapSpeedFromMph(float $speed, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_METRIC   => self::mphToKmh($speed),
            UnitConst::UNIT_STANDARD => self::kmhToMs(self::mphToKmh($speed)),
            default                  => $speed,
        };
    }

    public static function mphToKmh(float $mph): float
    {
        return round($mph * self::ONE_MI_IN_KM, 2);
    }

    public static function kmhToMs(float $kmh): float
    {
        return round($kmh / self::ONE_MS_IN_KMH, 2);
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    private static function mapSpeedFromKmh(float $speed, string $unit): float
    {
        return match ($unit) {
            UnitConst::UNIT_STANDARD => self::kmhToMs($speed),
            UnitConst::UNIT_IMPERIAL => self::kmhToMph($speed),
            default                  => $speed,
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public static function mapSunshine(int $sunshine, string $from, string $unit): int
    {
        return match ($from) {
            UnitConst::SUNSHINE_S => match ($unit) {
                UnitConst::UNIT_IMPERIAL => $sunshine,
                default                  => $sunshine * 60,
            },
            default => match ($unit) {
                UnitConst::UNIT_IMPERIAL => (int)round($sunshine / 60, 0),
                default                  => $sunshine,
            },
        };
    }

    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public static function mapPrecipitation(float $precipitation, string $from, string $unit): float
    {
        return match ($from) {
            UnitConst::PRECIPITAION_INCHES => match ($unit) {
                UnitConst::UNIT_IMPERIAL => $precipitation,
                default                  => $precipitation * 25.4,
            },
            default => match ($unit) {
                UnitConst::UNIT_IMPERIAL => round($precipitation / 25.4, 2),
                default                  => $precipitation,
            },
        };
    }
}
