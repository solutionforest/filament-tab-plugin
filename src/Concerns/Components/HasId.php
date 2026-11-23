<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;

trait HasId
{
    protected string|Closure|null $id = null;

    public function id(string|Closure|null $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        $id = $this->evaluate($this->id);

        return is_string($id) ? $id : null;
    }
}
