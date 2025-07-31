<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use Livewire\Component as LivewireComponent;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabsLayoutTab;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabLayoutComponent;
use SolutionForest\TabLayoutPlugin\Schemas\Components\LivewireContainer;
use SolutionForest\TabLayoutPlugin\Schemas\Components\TabContentContainer;

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
                $component instanceof LivewireContainer ||
                $component instanceof TabLayoutComponent
            ) {

                if (in_array(BelongsToContainer::class, class_uses_recursive($component))) {
                    $component = $component->container($this);
                }

                return $component;

            } elseif (
                (is_string($component) && is_subclass_of($component, LivewireComponent::class)) ||
                (is_object($component) && is_subclass_of($component, LivewireComponent::class))
            ) {

                $livewireComponentFqcn = is_string($component) ? $component : get_class($component);
                $livewireComponentParms = is_object($component) ? $component->all() : [];

                return LivewireContainer::make($livewireComponentFqcn)
                    ->data($livewireComponentParms);
            } elseif (is_string($component)) {

                return TabContentContainer::make($component);
            } elseif (is_object($component)) {
                if ($component instanceof TabContentContainer) {
                    return $component;
                }

                return TabContentContainer::make($component);
            }

            return null;

        }, $this->evaluate($this->components));

        if ($withHidden) {
            return $components;
        }

        return array_filter(
            $components,
            function ($component) {

                if (is_null($component)) {
                    return false;
                }

                // Check type of instance
                if (
                    ! (
                        $component instanceof LivewireContainer ||
                        $component instanceof TabContentContainer ||
                        $component instanceof TabLayoutComponent ||
                        $component instanceof TabsLayoutTab ||
                        $component instanceof LivewireComponent
                    )
                ) {
                    $targetTypes = collect([
                        LivewireContainer::class,
                        TabContentContainer::class,
                        TabLayoutComponent::class,
                        TabsLayoutTab::class,
                        LivewireComponent::class,
                    ])
                        ->map(fn ($fqcn) => class_basename($fqcn))
                        ->join(', ');
                    throw new \InvalidArgumentException(
                        "Components must be instances of {$targetTypes}."
                    );
                }

                if (method_exists($component, 'isHidden')) {
                    return ! $component->isHidden();
                }

                return true;
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
