# Changelog

### 0.7.0 (2023-07-22)

- breaking-change: allow to use any "php-http/client-implementation"

### 0.6.0 (2023-07-22)

- "DummyProvider" -> added for testing
- "WeatherCollection" is now countable
- breaking-change: "ProviderInterface" add missing method `getWeatherForecast()`
- breaking-change: "WeatherCollection" -> `add()` is now immutable

### 0.5.2 (2023-07-15)

- "BrightskyHttpProvider" -> clean-up debuggging stuff + fix test cases

### 0.5.1 (2023-07-15)

- "BrightskyHttpProvider" -> fix for NULL values from the API v2

### 0.5.0 (2023-07-15)

- "BrightskyHttpProvider" -> fix for NULL values from the API

### 0.4.0 (2023-07-15)

- "WeatherDto" -> add more units + "sunshine" info

### 0.3.0 (2023-07-15)

- "WeatherDto" -> add speed / temperature units into the DTO

### 0.2.0 (2023-07-15)

- added "WeatherDto->createFromJson()"

### 0.1.0 (2023-07-14)

- init release
