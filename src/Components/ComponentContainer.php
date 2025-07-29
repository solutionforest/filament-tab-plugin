<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Filament\Support\Components\ViewComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\BelongsToParentComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponents;

class ComponentContainer extends ViewComponent
{
    use BelongsToParentComponent;
    use CanBeHidden;
    use HasColumns;
    use HasComponents;

    protected array $meta = [];

    protected string $view = 'tab-layout-plugin::components.component-container';

    protected string $evaluationIdentifier = 'container';

    protected string $viewIdentifier = 'container';

    public static function make(): static
    {
        return app(static::class);
    }
}
