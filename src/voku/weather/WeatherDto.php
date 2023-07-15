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
        public readonly string $temperatureUnit,
        public readonly ?float $dewPoint,
        public readonly ?float $humidity,
        public readonly ?float $pressure,
        public readonly ?float $windSpeed,
        public readonly string $speedUnit,
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
        return $this->windSpeed . ' ' . $this->speedUnit;
    }

    public function getTemperatureWithUnit(): string
    {
        return $this->temperature . ' ' . $this->temperatureUnit;
    }

    public static function createFromJson(string $json): self {
        $data = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);

        if (is_array($data['sources'])) {
            $sources = [];
            foreach ($data['sources'] as $source) {
                if (is_array($source)) {
                    $sources[] = new WeatherSourceDto(...$source);
                }
            }
            $data['sources'] = $sources;
        }

        if (is_array($data['utcDateTime'])) {
            $utcDateTime = (new \DateTimeImmutable())->setTimezone(new \DateTimeZone('UTC'));
            $utcDateTime = $utcDateTime->setTimestamp(strtotime($data['utcDateTime']['date']));
            $data['utcDateTime'] = $utcDateTime;
        }

        return new WeatherDto(...$data);
    }
}
