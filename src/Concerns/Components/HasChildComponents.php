<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;
use InvalidArgumentException;
use SolutionForest\TabLayoutPlugin\Components\ComponentContainer;

trait HasChildComponents
{
    /**
     * @var array<int|string, mixed>|Closure
     */
    protected array|Closure $childComponents = [];

    /**
     * @var array<int|string, mixed>|Closure
     */
    protected array|Closure $childComponentsData = [];

    /**
     * @param  array<int|string, mixed>|Closure  $components
     */
    public function childComponents(array|Closure $components): static
    {
        $this->childComponents = $components;

        return $this;
    }

    /**
     * @param  array<int|string, mixed>|Closure  $components
     */
    public function schema(array|Closure $components): static
    {
        $this->childComponents($components);

        return $this;
    }

    /**
     * @deprecated Since version 1.0.0
     *
     * @param  array<int|string, mixed>|Closure  $schema
     */
    public function schemaComponentData(array|Closure $schema): static
    {
        $this->childComponentsData = $schema;

        return $this;
    }

    /**
     * Components' parameters (Need same array key with components)
     *
     * @return array<int|string, mixed>
     */
    public function getChildComponents(): array
    {
        $components = $this->evaluate($this->childComponents);

        return is_array($components) ? $components : [];
    }

    /**
     * @deprecated Since version 1.0.0
     *
     * @return array<int|string, mixed>
     */
    public function getChildComponentsData(): array
    {
        $data = $this->evaluate($this->childComponentsData);

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array-key|null  $key
     */
    public function getChildComponentContainer(int|string|null $key = null): ComponentContainer
    {
        if (filled($key)) {
            $containers = $this->getChildComponentContainers();

            if (! array_key_exists($key, $containers)) {
                throw new InvalidArgumentException("Child component container [{$key}] not found.");
            }

            return $containers[$key];
        }

        return ComponentContainer::make()
            ->parentComponent($this)
            ->components($this->getChildComponents());
    }

    /**
     * @return array<int|string, ComponentContainer>
     */
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
