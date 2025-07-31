<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use SolutionForest\TabLayoutPlugin\Concerns\Layouts\InteractsWithTab;
use SolutionForest\TabLayoutPlugin\Contracts\HasTabs;

class TabsWidget extends Widget implements HasTabs
{
    use InteractsWithTab;

    protected string $view = 'tab-layout-plugin::widgets.tabs-widget';

    protected int|string|array $columnSpan = 'full';

    public static function make(array $tabs = [], array $properties = []): WidgetConfiguration
    {
        return app(TabWidgetConfiguration::class, ['widget' => static::class, 'properties' => $properties, 'tabs' => $tabs]);
    }
}
