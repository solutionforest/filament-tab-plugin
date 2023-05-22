<?php

namespace SolutionForest\TabLayoutPlugin\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use Livewire\Component;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanBeHidden;
use SolutionForest\TabLayoutPlugin\Concerns\Components\CanSpanColumns;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasComponentData;

class ComponentWrapper extends Component
{
    use HasComponent;
    use HasComponentData;
    use CanBeHidden;
    use CanSpanColumns;
    use EvaluatesClosures;

    protected $rawComponent = null;

    public static function make(): static
    {
        $static = app(static::class);

        return $static;
    }

    public function mount($rawComponent) 
    {
        $this->rawComponent = $rawComponent;

        return $this;
    }

    public function getRawComponent()
    {
        return $this->rawComponent;
    }

    public function render()
    {
        return view('tab-layout-plugin::tabs.component-wrapper');
    }
}
