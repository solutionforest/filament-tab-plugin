<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use BackedEnum;
use Closure;

trait HasIcon
{
    protected string|Closure|BackedEnum|null $icon = null;

    public function icon(string|Closure|BackedEnum|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string|BackedEnum|null
    {
        $icon = $this->evaluate($this->icon);

        if (is_string($icon) || $icon instanceof BackedEnum || $icon === null) {
            return $icon;
        }

        return null;
    }
}
