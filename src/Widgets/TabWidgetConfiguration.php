<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class TabWidgetConfiguration extends WidgetConfiguration
{
    /**
     * @param  class-string<Widget>  $widget
     * @param  array<string, mixed>  $properties
     * @param  array<string, mixed>|TabWidgetContentConfiguration[]  $tabs
     */
    public function __construct(
        string $widget,
        array $properties = [],
        array $tabs = [],
    ) {
        $computedTabs = [];

        foreach (array_merge($tabs, $properties['tabs'] ?? [], $properties['tabComponents'] ?? []) as $item) {
            if (is_array($item)) {
                $item = TabWidgetContentConfiguration::parseFormArray($item);
            }
            if (! ($item instanceof TabWidgetContentConfiguration)) {
                throw new \InvalidArgumentException('Each tab must be an instance of '.TabWidgetContentConfiguration::class.'.');
            }
            $computedTabs[] = $item->toArray();
        }

        $properties['tabComponents'] = $computedTabs;

        unset($properties['tabs']);

        parent::__construct($widget, $properties);
    }

    /**
     * @param  array | TabWidgetContentConfiguration  $tab
     */
    public function tab($tab): static
    {
        $computedTab = null;
        if (is_array($tab)) {
            $computedTab = $tab;
        } elseif ($tab instanceof TabWidgetContentConfiguration) {
            $computedTab = $tab->toArray();
        } else {
            throw new \InvalidArgumentException('Each tab must be an instance of '.TabWidgetContentConfiguration::class.' or a valid array configuration.');
        }

        if ($computedTab) {
            $this->properties['tabComponents'][] = $computedTab;
        }

        return $this;
    }
}
