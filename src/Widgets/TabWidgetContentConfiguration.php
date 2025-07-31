<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Livewire\Component;
use SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema;

/**
 * @deprecated Use `SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema` instead.
 */
class TabWidgetContentConfiguration extends SimpleTabSchema
{
    /**
     * @param  class-string<Component>  $component
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public readonly string $component,
        public array $params,
        public readonly string $tabLabel,
        public readonly ?string $tabKey = null,
    ) {
        parent::__construct($tabLabel, $tabKey);
        $this->livewireComponent($component, $params);
    }
}
