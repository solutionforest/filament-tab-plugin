<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use InvalidArgumentException;
use SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema;

class TabWidgetConfiguration extends WidgetConfiguration
{
    /**
     * @param  class-string<Widget>  $widget
     * @param  array<string, mixed>  $properties
     * @param  array<string, mixed>|SimpleTabSchema[]  $tabs
     */
    public function __construct(
        string $widget,
        array $properties = [],
        array $tabs = [],
    ) {
        $computedTabs = [];

        foreach (array_merge(
            $tabs,
            $properties['tabs'] ?? [],
            $properties['tabComponents'] ?? [],
        ) as $item) {
            if (is_array($item)) {

                $item = SimpleTabSchema::parseFormArray($this->ensureSimpleTabSchemaArray($item));
            }

            if (! ($item instanceof SimpleTabSchema)) {
                throw new \InvalidArgumentException('Each tab must be an instance of '.SimpleTabSchema::class.'.');
            }

            $computedTabs[] = $item->toArray();
        }

        $properties['tabComponents'] = $computedTabs;

        unset($properties['tabs']);

        parent::__construct($widget, $properties);
    }

    /**
     * @param  array | SimpleTabSchema  $tab
     */
    public function tab($tab): static
    {
        $computedTab = null;
        if (is_array($tab)) {

            $computedTab = $this->ensureSimpleTabSchemaArray($tab);

        } elseif ($tab instanceof SimpleTabSchema) {

            $computedTab = $tab->toArray();

        } else {
            throw new InvalidArgumentException('Each tab must be an instance of '.TabWidgetContentConfiguration::class.' or a valid array configuration.');
        }

        if ($computedTab) {
            $this->properties['tabComponents'][] = $computedTab;
        }

        return $this;
    }

    private function ensureSimpleTabSchemaArray(array $item): array
    {
        if (! SimpleTabSchema::isValidArray($item)) {
            // Convert old TabWidgetContentConfiguration to SimpleTabSchema
            foreach ([
                'id' => ['tabKey', 'key'],
                'label' => ['tabLabel'],
                'content' => ['component'],
                'contentParams' => ['params'],
            ] as $newParamKey => $oldParamKeys) {

                foreach ($oldParamKeys as $oldParamKey) {
                    if (isset($item[$oldParamKey])) {
                        $item[$newParamKey] = $item[$oldParamKey];
                        unset($item[$oldParamKey]);
                    }
                }
            }
        }

        return $item;
    }
}
