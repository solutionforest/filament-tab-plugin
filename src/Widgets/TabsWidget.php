<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Filament\Widgets\Widget;
use SolutionForest\TabLayoutPlugin\Concerns\Layouts\InteractsWithTab;

class TabsWidget extends Widget
{
    use InteractsWithTab;

    protected static string $view = 'tab-layout-plugin::widgets.tabs-widget';

    protected int|string|array $columnSpan = 'full';
}
