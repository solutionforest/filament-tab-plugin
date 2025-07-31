<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Filament\Support\Components\ViewComponent;
// use SolutionForest\TabLayoutPlugin\Concerns\Components\BelongsToContainer;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasChildComponents;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasExtraAttributes;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasId;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasLabel;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasMaxWidth;

class FilamentComponent extends ViewComponent
{
    // use BelongsToContainer;
    use CanBeHidden;
    use CanSpanColumns;
    use HasChildComponents;
    use HasColumns;
    use HasExtraAttributes;
    use HasId;
    use HasLabel;
    use HasMaxWidth;

    protected string $evaluationIdentifier = 'component';
}
