<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Filament\Support\Components\ViewComponent;
use SolutionForest\TabLayoutPlugin\Concerns;

class ComponentContainer extends ViewComponent
{
    use Concerns\Components\BelongsToParentComponent;
    use Concerns\Components\CanBeHidden;
    use Concerns\Components\HasColumns;
    use Concerns\Components\HasComponents;

    protected array $meta = [];

    protected string $view = 'tab-layout-plugin::components.component-container';

    protected string $evaluationIdentifier = 'container';

    protected string $viewIdentifier = 'container';

    public static function make(): static
    {
        return app(static::class);
    }
}
