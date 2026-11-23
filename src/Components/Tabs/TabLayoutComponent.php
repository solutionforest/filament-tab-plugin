<?php

namespace SolutionForest\TabLayoutPlugin\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use RuntimeException;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

abstract class TabLayoutComponent
{
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;
    use HasComponent;
    use HasComponentData;

    public static function make(): static
    {
        $static = app(static::class);

        if (! $static instanceof static) {
            throw new RuntimeException(static::class.' could not be resolved from the container.');
        }

        return $static;
    }
}
