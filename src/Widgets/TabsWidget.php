<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Closure;
use Filament\Widgets\Widget;
use SolutionForest\TabLayoutPlugin\Concerns;

class TabsWidget extends Widget
{
    use Concerns\Layouts\InteractsWithTab;

    protected static string $view = 'tab-layout-plugin::widgets.tabs-widget';

    protected int | string | array $columnSpan = 'full';
}
