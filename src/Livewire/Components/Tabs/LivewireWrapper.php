<?php

namespace SolutionForest\TabLayoutPlugin\Livewire\Components\Tabs;

use Filament\Support\Concerns\EvaluatesClosures;
use Livewire\Component;
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

    /**
     * @var null | string | object
     */
    protected $rawComponent = null;

    public static function make(): static
    {
        $static = app(static::class);

        return $static;
    }

    /**
     * @param  null | string | object  $rawComponent
     * @return static
     */
    public function mount($rawComponent)
    {
        $this->rawComponent = $rawComponent;

        return $this;
    }

    /**
     * @return object|string|null
     */
    public function getRawComponent()
    {
        return $this->rawComponent;
    }

    public function render()
    {
        return view('tab-layout-plugin::tabs.component-wrapper');
    }
}
