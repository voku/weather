<?php

declare(strict_types=1);

namespace voku\weather\provider;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use voku\weather\constants\UnitConst;
use voku\weather\constants\WeatherConst;
use voku\weather\exception\ClientException;
use voku\weather\exception\InvalidValueException;
use voku\weather\exception\NoDataException;
use voku\weather\exception\ServerException;
use voku\weather\exception\WeatherException;
use voku\weather\WeatherCollection;
use voku\weather\WeatherDto;
use voku\weather\WeatherQueryDto;

abstract class AbstractHttpProvider implements ProviderInterface
{
    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    public function __construct(
        ?ClientInterface         $client = null,
        ?RequestFactoryInterface $requestFactory = null
    )
    {
        $this->client = $client ?: new \Httpful\Client();
        $this->requestFactory = $requestFactory ?: new \Httpful\Factory();
    }

    private function forceWeatherCollection(WeatherCollection|WeatherDto $weatherData): WeatherCollection
    {
        if ($weatherData instanceof WeatherDto) {
            return (new WeatherCollection())->add($weatherData);
        }

        return $weatherData;
    }

    /**
     * @return array<array-key,mixed>
     * @throws WeatherException
     * @throws ClientException
     *
     * @throws ServerException
     */
    private function getRawResponse(string $url): array
    {
        $request = $this->getRequest('GET', $url);
        $response = $this->getResponse($request);

        try {
            return json_decode($response, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new WeatherException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function getRequest(string $method, string $url): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $url);
    }

    /**
     * @throws ClientException
     * @throws ServerException
     */
    private function getResponse(RequestInterface $request): string
    {
        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();

        if ($statusCode >= 400 && $statusCode < 500) {
            throw new ClientException($body . ' | ' . $request->getUri(), $statusCode);
        }

        if ($statusCode >= 500) {
            throw new ServerException($body . ' | ' . $request->getUri(), $statusCode);
        }

        return $body;
    }

    /**
     * @param array<array-key,mixed>       $rawData
     *
     * @phpstan-param WeatherConst::TYPE_* $type
     * @phpstan-param UnitConst::UNIT_*    $unit
     */
    abstract protected function mapRawData(
        float  $latitude,
        float  $longitude,
        array  $rawData,
        string $type,
        string $unit
    ): WeatherDto|WeatherCollection;

    /**
     * @throws ClientException
     * @throws NoDataException
     * @throws ServerException
     * @throws WeatherException
     */
    public function getWeatherCurrent(WeatherQueryDto $query): WeatherDto
    {
        $url = $this->getWeatherCurrentUrl($query);
        $rawResponse = $this->getRawResponse($url);

        $mappedRawData = $this->mapRawData(
            $query->latitude,
            $query->longitude,
            $rawResponse,
            WeatherConst::TYPE_CURRENT,
            $query->unit
        );

        $currentWeather = null;
        if ($mappedRawData instanceof WeatherCollection) {
            $currentWeather = $mappedRawData->getCurrentWeather();
        }
        if ($mappedRawData instanceof WeatherDto) {
            $currentWeather = $mappedRawData;
        }
        if ($currentWeather === null) {
            throw new NoDataException();
        }

        return $currentWeather;
    }

    abstract protected function getWeatherCurrentUrl(WeatherQueryDto $query): string;

    /**
     * @throws ClientException
     * @throws InvalidValueException
     * @throws NoDataException
     * @throws ServerException
     * @throws WeatherException
     */
    public function getWeatherForecast(WeatherQueryDto $query): WeatherDto
    {
        $url = $this->getWeatherForecastUrl($query);
        $rawResponse = $this->getRawResponse($url);

        $mappedRawData = $this->mapRawData(
            $query->latitude,
            $query->longitude,
            $rawResponse,
            WeatherConst::TYPE_HISTORICAL,
            $query->unit
        );

        $historicalWeather = null;
        if (
            $mappedRawData instanceof WeatherCollection
            &&
            $query->dateTime !== null
        ) {
            $historicalWeather = $mappedRawData->getClosest($query->dateTime);
        }

        if ($mappedRawData instanceof WeatherDto) {
            $historicalWeather = $mappedRawData;
        }

        if ($historicalWeather === null) {
            throw new NoDataException();
        }

        return $historicalWeather;
    }

    abstract protected function getWeatherForecastUrl(WeatherQueryDto $query): string;

    /**
     * @throws ClientException
     * @throws NoDataException
     * @throws ServerException
     * @throws WeatherException
     */
    public function getWeatherForecastCollection(WeatherQueryDto $query): WeatherCollection
    {
        $url = $this->getWeatherForecastCollectionUrl($query);
        $rawResponse = $this->getRawResponse($url);

        $weatherData = $this->mapRawData(
            $query->latitude,
            $query->longitude,
            $rawResponse,
            WeatherConst::TYPE_FORECAST,
            $query->unit
        );

        return $this->forceWeatherCollection($weatherData);
    }

    abstract protected function getWeatherForecastCollectionUrl(WeatherQueryDto $query): string;

    /**
     * @throws ClientException
     * @throws InvalidValueException
     * @throws NoDataException
     * @throws ServerException
     * @throws WeatherException
     */
    public function getWeatherHistorical(WeatherQueryDto $query): WeatherDto
    {
        $url = $this->getWeatherHistoricalUrl($query);
        $rawResponse = $this->getRawResponse($url);

        $mappedRawData = $this->mapRawData(
            $query->latitude,
            $query->longitude,
            $rawResponse,
            WeatherConst::TYPE_HISTORICAL,
            $query->unit
        );

        $historicalWeather = null;
        if (
            $mappedRawData instanceof WeatherCollection
            &&
            $query->dateTime !== null
        ) {
            $historicalWeather = $mappedRawData->getClosest($query->dateTime);
        }

        if ($mappedRawData instanceof WeatherDto) {
            $historicalWeather = $mappedRawData;
        }

        if ($historicalWeather === null) {
            throw new NoDataException();
        }

        return $historicalWeather;
    }

    abstract protected function getWeatherHistoricalUrl(WeatherQueryDto $query): string;

    /**
     * @throws ClientException
     * @throws ServerException
     * @throws WeatherException
     */
    public function getWeatherHistoricalCollection(WeatherQueryDto $query): WeatherCollection
    {
        $url = $this->getWeatherHistoricalCollectionUrl($query);
        $rawResponse = $this->getRawResponse($url);

        $historicalData = $this->mapRawData(
            $query->latitude,
            $query->longitude,
            $rawResponse,
            WeatherConst::TYPE_HISTORICAL,
            $query->unit
        );

        return $this->forceWeatherCollection($historicalData);
    }

    abstract protected function getWeatherHistoricalCollectionUrl(WeatherQueryDto $query): string;
}
