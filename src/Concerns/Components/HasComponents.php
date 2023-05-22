<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Illuminate\Support\Str;
use SolutionForest\TabLayoutPlugin\Components\ComponentContainer;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use Closure;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabsLayoutTab;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabContainer;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabLayoutComponent;

trait HasComponents
{
    protected array | Closure $components = [];

    protected array | Closure $componentsData = [];

    public function components(array | Closure $components): static
    {
        $this->components = $components;

        return $this;
    }

    public function schema(array | Closure $components): static
    {
        $this->components($components);

        return $this;
    }

    /**
     * @deprecated Since version 1.0.0
     */
    public function schemaComponentData(array | Closure $data): static
    {
        $this->componentsData = $data;

        return $this;
    }

    public function getComponents(bool $withHidden = false): array
    {
        $components = array_map(function (TabContainer|TabLayoutComponent|TabsLayoutTab $component) {

            if ($component instanceof FilamentComponent) {

                $component->container($this);
            }

            return $component;
        }, $this->evaluate($this->components));

        if ($withHidden) {
            return $components;
        }

        return array_filter(
            $components,
            fn (TabContainer|TabLayoutComponent|TabsLayoutTab $component) => ! $component->isHidden(),
        );
    }

    /**
     * @deprecated Since version 1.0.0
     */
    public function getChildComponentData($key): array
    {
        $componentData = array_map(function ($data) {
            if (is_null($data)) {
                return [];
            }
            else if (! is_array($data)) {
                return [$data];
            }

            return $data;

        }, $this->evaluate($this->componentsData));

        return data_get($componentData, $key, []);
    }
}
