<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use Livewire\Component as LivewireComponent;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabsLayoutTab;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabContainer;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabLayoutComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\BelongsToContainer;

trait HasComponents
{
    protected array|Closure $components = [];

    protected array|Closure $componentsData = [];

    public function components(array|Closure $components): static
    {
        $this->components = $components;

        return $this;
    }

    public function schema(array|Closure $components): static
    {
        $this->components($components);

        return $this;
    }

    /**
     * @deprecated Since version 1.0.0
     */
    public function schemaComponentData(array|Closure $data): static
    {
        $this->componentsData = $data;

        return $this;
    }

    public function getComponents(bool $withHidden = false): array
    {
        $components = array_map(function ($component) {

            if (
                $component instanceof FilamentComponent || 
                $component instanceof TabContainer || 
                $component instanceof TabLayoutComponent
            ) {

                if (in_array(BelongsToContainer::class, class_uses_recursive($component))) {
                    $component = $component->container($this);
                }

                return $component;

            } elseif (is_string($component)) {

                if (is_subclass_of($component, LivewireComponent::class)) {
                    return TabContainer::make($component);
                }

                return TabContainer::make('tab-layout-plugin::component-wrapper')
                    ->data(['rawComponent' => str($component)->toHtmlString()]);

            } elseif (is_object($component)) {

                // Check if the component is a Livewire component
                if (is_subclass_of($component, LivewireComponent::class)) {
                    return TabContainer::make(get_class($component))
                        ->data($component->all());
                }

                return TabContainer::make('tab-layout-plugin::component-wrapper')
                    ->data(['rawComponent' => $component]);

            }

            return null;

        }, $this->evaluate($this->components));

        if ($withHidden) {
            return $components;
        }

        return array_filter(
            $components,
            function (TabContainer|TabLayoutComponent|TabsLayoutTab|LivewireComponent|null $component) {
                if ($component && method_exists($component, 'isHidden')) {
                    return ! $component->isHidden();
                } elseif ($component) {

                    return true;
                }

                return false;
            }
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
            } elseif (! is_array($data)) {
                return [$data];
            }

            return $data;

        }, $this->evaluate($this->componentsData));

        return data_get($componentData, $key, []);
    }
}
