<?php

declare(strict_types=1);

namespace voku\weather;

use voku\weather\constants\WeatherConst;

final class WeatherCollection
{
    private ?WeatherDto $current = null;

    /**
     * @var list<WeatherDto>
     */
    private array $historical = [];

    /**
     * @var list<WeatherDto>
     */
    private array $forecast = [];

    public function add(WeatherDto $weather): self
    {
        if ($weather->type === WeatherConst::TYPE_CURRENT) {
            $this->current = $weather;
        }

        if ($weather->type === WeatherConst::TYPE_HISTORICAL) {
            $this->historical[] = $weather;
            usort($this->historical, [$this, 'sortByDate']);
        }

        if ($weather->type === WeatherConst::TYPE_FORECAST) {
            $this->forecast[] = $weather;
            usort($this->forecast, [$this, 'sortByDate']);
        }

        return $this;
    }

    public function getClosest(\DateTimeInterface $dateTime): ?WeatherDto
    {
        // init
        $closestWeatherData = null;
        $difference = null;

        foreach ($this->getAll() as $weather) {
            if ($weather->utcDateTime === null) {
                continue;
            }

            $checkDifference = abs($weather->utcDateTime->getTimestamp() - $dateTime->getTimestamp());
            if (
                $difference === null
                ||
                $checkDifference < $difference
            ) {
                $closestWeatherData = $weather;
                $difference = $checkDifference;
            }
        }

        return $closestWeatherData;
    }

    public function getCurrentWeather(): ?WeatherDto
    {
        return $this->current;
    }

    /**
     * @return list<WeatherDto>
     */
    public function getHistorical(): array
    {
        return $this->historical;
    }

    /**
     * @return list<WeatherDto>
     */
    public function getForecast(): array
    {
        return $this->forecast;
    }

    /**
     * @return list<WeatherDto>
     */
    public function getAll(): array
    {
        $weatherArray = $this->current ? [$this->current] : [];
        $weatherArray += $this->historical;
        $weatherArray += $this->forecast;

        return $weatherArray;
    }

    private function sortByDate(WeatherDto $a, WeatherDto $b): int
    {
        if ($a->utcDateTime?->getTimestamp() === $b->utcDateTime?->getTimestamp()) {
            return 0;
        }

        return $a->utcDateTime?->getTimestamp() < $b->utcDateTime?->getTimestamp() ? -1 : 1;
    }
}
