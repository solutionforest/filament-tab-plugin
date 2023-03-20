<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Illuminate\Support\Str;
use SolutionForest\TabLayoutPlugin\Components\ComponentContainer;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use Closure;

trait HasComponents
{
    protected array | Closure $components = [];

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

    public function getComponents(bool $withHidden = false): array
    {
        $components = array_map(function (\Filament\Support\Components\Component|\Livewire\Component|\Illuminate\View\View|\Filament\Resources\Form|string $component) {

            if ($component instanceof FilamentComponent) {

                $component->container($this);
            } else if (is_string($component) && strip_tags($component)) {
                return Str::of($component)->toHtmlString();

            }

            return $component;
        }, $this->evaluate($this->components));

        if ($withHidden) {
            return $components;
        }

        return array_filter(
            $components,
            fn ($component) => $component instanceof FilamentComponent ? ! $component->isHidden() : true,
        );
    }
}
