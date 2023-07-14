<?php

declare(strict_types=1);

namespace voku\weather;

/**
 * @immutable
 */
final class WeatherSourceDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $url = null
    ) {
    }
}
