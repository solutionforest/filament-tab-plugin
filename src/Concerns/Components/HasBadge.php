<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;

trait HasBadge
{
    protected string|Closure|null $badge = null;

    public function badge(string|Closure|null $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->evaluate($this->badge);
    }
}
