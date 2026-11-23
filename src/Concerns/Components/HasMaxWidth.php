<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;

trait HasMaxWidth
{
    protected string|Closure|null $maxWidth = null;

    public function maxWidth(string|Closure|null $width): static
    {
        $this->maxWidth = $width;

        return $this;
    }

    public function getMaxWidth(): ?string
    {
        $width = $this->evaluate($this->maxWidth);

        return is_string($width) ? $width : null;
    }
}
