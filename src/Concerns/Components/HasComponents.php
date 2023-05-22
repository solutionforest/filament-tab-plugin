<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Illuminate\Support\Str;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use SolutionForest\TabLayoutPlugin\Components\Tabs\ComponentWrapper;
use Closure;
use Livewire\Component as LivewireComponent;
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
        $components = array_map(function ($component) {

            if ($component instanceof FilamentComponent) {

                return $component->container($this);
            }
            else if ($component instanceof TabContainer || $component instanceof TabLayoutComponent) {

                return $component;
            }
            else if (is_object($component)) {
                
                if (\Livewire\Livewire::getAlias(get_class($component))) {

                    return TabContainer::make(\Livewire\Livewire::getAlias(get_class($component)));
                } 
                $livewireAlias = \Livewire\Livewire::getAlias(ComponentWrapper::class);
                return TabContainer::make($livewireAlias)->data(['rawComponent' => $component]);
                
            } else if (is_string($component)) {

                $livewireAlias = \Livewire\Livewire::getAlias(ComponentWrapper::class);
                return TabContainer::make($livewireAlias)->data(['rawComponent' => new \Illuminate\Support\HtmlString($component)]);
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
                }
                else if ($component) {
                    
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
            }
            else if (! is_array($data)) {
                return [$data];
            }

            return $data;

        }, $this->evaluate($this->componentsData));

        return data_get($componentData, $key, []);
    }
}
