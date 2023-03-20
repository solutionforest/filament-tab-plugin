<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use SolutionForest\TabLayoutPlugin\Concerns;
use Filament\Support\Components\ViewComponent;

class FilamentComponent extends ViewComponent
{
    use Concerns\Components\BelongsToContainer;
    use Concerns\Components\CanBeHidden;
    use Concerns\Components\CanSpanColumns;
    use Concerns\Components\HasChildComponents;
    use Concerns\Components\HasId;
    use Concerns\Components\HasLabel;
    use Concerns\Components\HasMaxWidth;
    use Concerns\Components\HasColumns;
    use Concerns\Components\HasExtraAttributes;

    protected string $evaluationIdentifier = 'component';
}
