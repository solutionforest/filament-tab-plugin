<?php

namespace SolutionForest\TabLayoutPlugin\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

class TabContainer
{
    use HasComponentData;
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;

    public function __construct(
        protected ?string $component = null,
    ) {}
    
    public static function make(string $component): static
    {
        $static = app(static::class, ['component' => $component]);

        if (method_exists($static, 'configure')) {
            $static->configure();
        }

        return $static;
    }
    
    public function component(string $component): static
    {
        $this->component = $component;

        return $this;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }
}
