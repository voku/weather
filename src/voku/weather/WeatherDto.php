<?php

declare(strict_types=1);

namespace voku\weather;

use voku\weather\constants\UnitConst;
use voku\weather\constants\WeatherConst;

/**
 * @immutable
 */
final class WeatherDto
{
    public function __construct(
        /**
         * @var UnitConst::UNIT_*
         */
        public readonly string $unit,
        /**
         * @var WeatherSourceDto[]
         */
        public readonly array $sources,
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly float $temperature,
        public readonly ?float $dewPoint,
        public readonly ?float $humidity,
        public readonly ?float $pressure,
        public readonly ?float $windSpeed,
        public readonly ?float $windDirection,
        public readonly ?float $precipitation,
        public readonly ?float $cloudCover,
        public readonly ?\DateTimeInterface $utcDateTime,
        /**
         * @var WeatherConst::TYPE_*|null
         */
        public readonly ?string $type,
        /**
         * Condition (dry, fog, rain, ...)
         *
         * @var WeatherConst::CODE_*|null
         */
        public readonly ?int $weatherCode,
        /**
         * @var WeatherConst::ICON_*|null
         */
        public readonly ?string $icon
    ) {
    }

    public function getWindSpeedWithUnit(): string
    {
        return $this->windSpeed . ' ' . $this->getSpeedUnit();
    }

    public function getTemperatureWithUnit(): string
    {
        return $this->temperature . ' ' . $this->getTemperatureUnit();
    }

    /**
     * @phpstan-return '°F'|'K'|'°C'
     */
    public function getTemperatureUnit(): string
    {
        return UnitHelper::getTemperatureUnit($this->unit);
    }

    /**
     * @phpstan-return 'km/h'|'mph'|'ms'
     */
    public function getSpeedUnit(): string
    {
        return UnitHelper::getSpeedUnit($this->unit);
    }
}
