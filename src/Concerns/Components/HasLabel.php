<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

trait HasLabel
{
    protected bool|Closure $isLabelHidden = false;

    protected string|Htmlable|Closure|null $label = null;

    protected bool $shouldTranslateLabel = false;

    public function disableLabel(bool|Closure $condition = true): static
    {
        $this->isLabelHidden = $condition;

        return $this;
    }

    public function label(string|Htmlable|Closure|null $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function translateLabel(bool $shouldTranslateLabel = true): static
    {
        $this->shouldTranslateLabel = $shouldTranslateLabel;

        return $this;
    }

    public function getLabel(): string|Htmlable|null
    {
        $label = $this->evaluate($this->label);

        if (is_string($label) && $this->shouldTranslateLabel) {
            return __($label);
        }

        if (is_string($label) || $label instanceof Htmlable || $label === null) {
            return $label;
        }

        return null;
    }

    public function isLabelHidden(): bool
    {
        return (bool) $this->evaluate($this->isLabelHidden);
    }
}
