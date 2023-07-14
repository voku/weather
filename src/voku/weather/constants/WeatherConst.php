<?php

declare(strict_types=1);

namespace voku\weather\constants;

final class WeatherConst
{
    public const CODE_DRY = 10;

    public const CODE_FOG = 20;

    public const CODE_RAIN = 30;

    public const CODE_SNOW_RAIN = 40;

    public const CODE_SNOW = 50;

    public const CODE_HAIL = 60;

    public const CODE_THUNDERSTORM = 70;

    public const ICON_CLEAR_DAY = 'day-sunny';

    public const ICON_CLEAR_NIGHT = 'night-clear';

    public const ICON_CLOUDY_DAY = 'day-cloudy';

    public const ICON_CLOUDY_NIGHT = 'night-cloudy';

    public const ICON_CLOUDY = 'cloudy';

    public const ICON_RAIN = 'rain';

    public const ICON_FOG = 'fog';

    public const ICON_SNOW = 'snow';

    public const ICON_THUNDERSTORM = 'thunderstorm';

    public const ICON_SNOW_RAIN = 'sleet';

    public const ICON_HAIL = 'hail';

    public const ICON_WIND = 'strong-wind';

    public const TYPE_CURRENT = 'current';

    public const TYPE_HISTORICAL = 'historical';

    public const TYPE_FORECAST = 'forecast';
}
