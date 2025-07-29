<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

trait HasComponent
{
    protected ?string $component = null;

    public function component(string $component): static
    {
        $this->component = $component;

        return $this;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }
}
