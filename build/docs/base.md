[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

[![Build Status](https://github.com/voku/weather/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/voku/weather/actions)
[![codecov.io](http://codecov.io/github/voku/weather/coverage.svg?branch=main)](http://codecov.io/github/voku/weather?branch=main)

# Weather Data Wrapper

This is a simple wrapper around the "Bright Sky" (https://brightsky.dev/) weather api.
+ you can simply replace the weather api by implementing another weather provider class.

### DEMO:
[http://weather-demo.suckup.de/](http://weather-demo.suckup.de/)


### Install via "composer require"
```shell
composer require voku/weather
```

### Usage:

```php

use voku\weather\provider\BrightskyHttpProvider;
use voku\weather\WeatherQueryDto;

require_once __DIR__ . '/vendor/autoload.php'; // example path

$latitude = 48.137154;
$longitude = 11.576124;
$dateTime = new \DateTimeImmutable('2023-01-01 12:00:00');

$weatherQuery = new WeatherQueryDto(
    $latitude,
    $longitude,
    $dateTime
);

$weather = (new BrightskyHttpProvider())->getWeatherHistorical($weatherQuery);

echo $weather->temperature; // 17.1
```

Example 1: (temperature with unit)

```php
echo $weather->getTemperatureWithUnit(); // 17.1 °C
```

Example 2: (wind-speed with unit)

```php
echo $weather->getWindSpeedWithUnit(); // 9 km/h
```


### Unit Test:

1) [Composer](https://getcomposer.org) is a prerequisite for running the tests.

```
composer install
```

2) The tests can be executed by running this command from the root directory:

```bash
./vendor/bin/phpunit
```

## AbstractHttpProvider methods

%__functions_index__voku\weather\provider\AbstractHttpProvider__%

%__functions_list__voku\weather\provider\AbstractHttpProvider__%

### Thanks

- Thanks to [GitHub](https://github.com) (Microsoft) for hosting the code and a good infrastructure including Issues-Management, etc.
- Thanks to [IntelliJ](https://www.jetbrains.com) as they make the best IDEs for PHP and they gave me an open source license for PhpStorm!
- Thanks to [StyleCI](https://styleci.io/) for the simple but powerful code style check.
- Thanks to [PHPStan](https://github.com/phpstan/phpstan) && [Psalm](https://github.com/vimeo/psalm) for really great Static analysis tools and for discover bugs in the code!
