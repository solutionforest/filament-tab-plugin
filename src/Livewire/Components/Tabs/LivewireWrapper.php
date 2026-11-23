<?php

namespace SolutionForest\TabLayoutPlugin\Livewire\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use RuntimeException;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

class LivewireWrapper extends Component
{
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;
    use HasComponent;
    use HasComponentData;

    protected string|object|null $rawComponent = null;

    public static function make(): static
    {
        $static = app(static::class);

        if (! $static instanceof static) {
            throw new RuntimeException(static::class.' could not be resolved from the container.');
        }

        return $static;
    }

    public function mount(string|object|null $rawComponent): static
    {
        $this->rawComponent = $rawComponent;

        return $this;
    }

    public function getRawComponent(): string|object|null
    {
        return $this->rawComponent;
    }

    public function render(): View
    {
        return view('tab-layout-plugin::tabs.component-wrapper'); // @phpstan-ignore argument.type (package view name)
    }
}
