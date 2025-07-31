<?php

namespace SolutionForest\TabLayoutPlugin\Schemas\Components;

// use Illuminate\View\Component as ViewComponent;
use Filament\Support\Concerns\EvaluatesClosures;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

class LivewireContainer // extends ViewComponent
{
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;
    use HasComponent;
    use HasComponentData;

    public function __construct(?string $component = null)
    {
        $this->component($component);
    }

    public static function make(string $component): static
    {
        $static = app(static::class, ['component' => $component]);

        return $static;
    }
}
