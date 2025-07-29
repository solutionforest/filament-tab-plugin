<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use SolutionForest\TabLayoutPlugin\Components\ComponentContainer;

trait HasChildComponents
{
    protected array|Closure $childComponents = [];

    protected array|Closure $childComponentsData = [];

    public function childComponents(array|Closure $components): static
    {
        $this->childComponents = $components;

        return $this;
    }

    public function schema(array|Closure $components): static
    {
        $this->childComponents($components);

        return $this;
    }

    /**
     * @deprecated Since version 1.0.0
     */
    public function schemaComponentData(array|Closure $schema): static
    {
        $this->childComponentsData = $schema;

        return $this;
    }

    /**
     * Components' parameters (Need same array key with components)
     */
    public function getChildComponents(): array
    {
        return $this->evaluate($this->childComponents);
    }

    /**
     * @deprecated Since version 1.0.0
     */
    public function getChildComponentsData(): array
    {
        return $this->evaluate($this->childComponentsData);
    }

    public function getChildComponentContainer($key = null): ComponentContainer
    {
        if (filled($key)) {
            return $this->getChildComponentContainers()[$key];
        }

        return ComponentContainer::make()
            ->parentComponent($this)
            ->components($this->getChildComponents());
    }

    public function getChildComponentContainers(bool $withHidden = false): array
    {
        if (! $this->hasChildComponentContainer($withHidden)) {
            return [];
        }

        return [$this->getChildComponentContainer()];
    }

    public function hasChildComponentContainer(bool $withHidden = false): bool
    {
        if ((! $withHidden) && $this->isHidden()) {
            return false;
        }

        if ($this->getChildComponents() === []) {
            return false;
        }

        return true;
    }
}
