<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use Illuminate\View\ComponentAttributeBag;

trait HasExtraAttributes
{
    /**
     * @var array<int, array<string, mixed>|Closure>
     */
    protected array $extraAttributes = [];

    /**
     * @param  array<string, mixed>|Closure  $attributes
     */
    public function extraAttributes(array|Closure $attributes, bool $merge = false): static
    {
        if ($merge) {
            $this->extraAttributes[] = $attributes;
        } else {
            $this->extraAttributes = [$attributes];
        }

        return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getExtraAttributes(): array
    {
        $temporaryAttributeBag = new ComponentAttributeBag;

        foreach ($this->extraAttributes as $extraAttributes) {
            $evaluated = $this->evaluate($extraAttributes);

            if (is_array($evaluated)) {
                $temporaryAttributeBag = $temporaryAttributeBag->merge($evaluated);
            }
        }

        return $temporaryAttributeBag->getAttributes();
    }

    public function getExtraAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraAttributes());
    }
}
