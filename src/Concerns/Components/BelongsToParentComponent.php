<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;

trait BelongsToParentComponent
{
    protected ?FilamentComponent $parentComponent = null;

    public function parentComponent(FilamentComponent $component): static
    {
        $this->parentComponent = $component;

        return $this;
    }

    public function getParentComponent(): ?FilamentComponent
    {
        return $this->parentComponent;
    }
}
