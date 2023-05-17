<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use SolutionForest\TabLayoutPlugin\Concerns\Components\{BelongsToContainer, CanBeHidden, CanSpanColumns, HasChildComponents, HasId, HasLabel, HasMaxWidth, HasColumns, HasExtraAttributes};
use Filament\Support\Components\ViewComponent;

class FilamentComponent extends ViewComponent
{
    use BelongsToContainer;
    use CanBeHidden;
    use CanSpanColumns;
    use HasChildComponents;
    use HasId;
    use HasLabel;
    use HasMaxWidth;
    use HasColumns;
    use HasExtraAttributes;

    protected string $evaluationIdentifier = 'component';
}
