<?php

declare(strict_types=1);

namespace voku\weather;

use voku\weather\constants\WeatherConst;

final class WeatherCollection implements \Countable
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

    /**
     * @return self
     *               <p>(Immutable) Returns a new collection.</p>
     */
    public function add(WeatherDto $weather, bool $forceSort = true): self
    {
        $that = clone $this;

        if ($weather->type === WeatherConst::TYPE_CURRENT) {
            $that->current = $weather;
        }

        if ($weather->type === WeatherConst::TYPE_HISTORICAL) {
            $that->historical[] = $weather;
            if ($forceSort) {
                usort($that->historical, [$that, 'sortByDate']);
            }
        }

        if ($weather->type === WeatherConst::TYPE_FORECAST) {
            $that->forecast[] = $weather;
            if ($forceSort) {
                usort($that->forecast, [$that, 'sortByDate']);
            }
        }

        return $that;
    }

    /**
     * @return self
     *               <p>(Immutable) Returns a new collection.</p>
     */
    public function sortWeatherData(): self {
        $that = clone $this;

        usort($that->historical, [$that, 'sortByDate']);
        usort($that->forecast, [$that, 'sortByDate']);

        return $that;
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

    public function count(): int
    {
        return count($this->getAll());
    }

    private function sortByDate(WeatherDto $a, WeatherDto $b): int
    {
        if ($a->utcDateTime?->getTimestamp() === $b->utcDateTime?->getTimestamp()) {
            return 0;
        }

        return $a->utcDateTime?->getTimestamp() < $b->utcDateTime?->getTimestamp() ? -1 : 1;
    }
}
