<?php

namespace SolutionForest\TabLayoutPlugin\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

abstract class TabLayoutComponent
{
    use HasComponentData;
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;

    protected string $component;
    
    public static function make(): static
    {
        $static = app(static::class);

        if (method_exists($static, 'configure')) {
            $static->configure();
        }

        return $static;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }
}
