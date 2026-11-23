<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Filament\Support\Components\ViewComponent;
use RuntimeException;
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

    /**
     * @var array<string, mixed>
     */
    protected array $meta = [];

    protected string $view = 'tab-layout-plugin::components.component-container';

    protected string $evaluationIdentifier = 'container';

    protected string $viewIdentifier = 'container';

    public static function make(): static
    {
        $instance = app(static::class);

        if (! $instance instanceof static) {
            throw new RuntimeException(static::class.' could not be resolved from the container.');
        }

        return $instance;
    }
}
