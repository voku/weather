<?php

declare(strict_types=1);

namespace voku\weather;

use voku\weather\constants\UnitConst;

final class WeatherQueryDto
{
    /**
     * @phpstan-param UnitConst::UNIT_* $unit
     */
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly ?\DateTimeInterface $dateTime = null,
        public readonly ?\DateTimeInterface $lastDateTime = null,
        /**
         * @var UnitConst::UNIT_*
         */
        public readonly string $unit = UnitConst::UNIT_METRIC
    ) {
    }
}
