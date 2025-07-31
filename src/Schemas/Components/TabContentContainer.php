<?php

namespace SolutionForest\TabLayoutPlugin\Schemas\Components;

use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\View\Component as ViewComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;

class TabContentContainer extends ViewComponent
{
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;

    protected mixed $object = null;

    public function __construct(mixed $object = null)
    {
        $this->object = $object;
    }

    public static function make(mixed $object): static
    {
        $static = app(static::class, ['object' => $object]);

        return $static;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('tab-layout-plugin::schemas.components.tab-content-container', [
            'object' => $this->object,
        ]);
    }
}
