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
    /**
     * @var array<int|string, mixed>|Closure
     */
    protected array|Closure $components = [];

    /**
     * @var array<int|string, mixed>|Closure
     */
    protected array|Closure $componentsData = [];

    /**
     * @param  array<int|string, mixed>|Closure  $components
     */
    public function components(array|Closure $components): static
    {
        $this->components = $components;

        return $this;
    }

    /**
     * @param  array<int|string, mixed>|Closure  $components
     */
    public function schema(array|Closure $components): static
    {
        $this->components($components);

        return $this;
    }

    /**
     * @deprecated Since version 1.0.0
     *
     * @param  array<int|string, mixed>|Closure  $data
     */
    public function schemaComponentData(array|Closure $data): static
    {
        $this->componentsData = $data;

        return $this;
    }

    /**
     * @return array<int|string, FilamentComponent|LivewireContainer|TabContentContainer|TabLayoutComponent>
     */
    public function getComponents(bool $withHidden = false): array
    {
        $evaluated = $this->evaluate($this->components);

        if (! is_array($evaluated)) {
            return [];
        }

        $components = [];

        foreach ($evaluated as $key => $component) {
            $resolved = $this->resolveComponent($component);

            if ($resolved === null) {
                continue;
            }

            if (! $withHidden) {
                if (
                    ! (
                        $resolved instanceof LivewireContainer ||
                        $resolved instanceof TabContentContainer ||
                        $resolved instanceof TabLayoutComponent ||
                        $resolved instanceof TabsLayoutTab
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

                if ($resolved->isHidden()) {
                    continue;
                }
            }

            $components[$key] = $resolved;
        }

        return $components;
    }

    private function resolveComponent(mixed $component): FilamentComponent|LivewireContainer|TabContentContainer|TabLayoutComponent|null
    {
        if (
            $component instanceof FilamentComponent ||
            $component instanceof LivewireContainer ||
            $component instanceof TabLayoutComponent
        ) {
            if (method_exists($component, 'container')) {
                $withContainer = $component->container($this);

                if (
                    $withContainer instanceof FilamentComponent ||
                    $withContainer instanceof LivewireContainer ||
                    $withContainer instanceof TabLayoutComponent
                ) {
                    $component = $withContainer;
                }
            }

            return $component;
        }

        if (
            (is_string($component) && is_subclass_of($component, LivewireComponent::class)) ||
            (is_object($component) && is_subclass_of($component, LivewireComponent::class))
        ) {
            $livewireComponentFqcn = is_string($component) ? $component : get_class($component);
            $livewireComponentParms = is_object($component) ? $component->all() : [];

            return LivewireContainer::make($livewireComponentFqcn)
                ->data(self::stringKeyedArray(is_array($livewireComponentParms) ? $livewireComponentParms : []));
        }

        if (is_string($component)) {
            return TabContentContainer::make($component);
        }

        if (is_object($component)) {
            if ($component instanceof TabContentContainer) {
                return $component;
            }

            return TabContentContainer::make($component);
        }

        return null;
    }

    /**
     * @deprecated Since version 1.0.0
     *
     * @param  array-key|null  $key
     * @return array<int|string, mixed>
     */
    public function getChildComponentData(int|string|null $key): array
    {
        $evaluated = $this->evaluate($this->componentsData);
        $componentData = is_array($evaluated)
            ? array_map(function (mixed $data): array {
                if (is_null($data)) {
                    return [];
                } elseif (! is_array($data)) {
                    return [$data];
                }

                return $data;

            }, $evaluated)
            : [];

        $result = data_get($componentData, $key, []);

        return is_array($result) ? $result : [];
    }

    /**
     * @param  array<int|string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function stringKeyedArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
