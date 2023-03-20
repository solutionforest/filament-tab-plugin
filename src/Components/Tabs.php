<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Closure;
use Filament\Support\Components\ViewComponent;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class Tabs extends FilamentComponent
{
    use HasExtraAlpineAttributes;

    protected string $view = 'tab-layout-plugin::components.tabs';

    public int | Closure $activeTab = 1;

    protected string | Closure | null $tabQueryStringKey = null;

    public function __construct()
    {
        $this->id(uniqid());
    }

    public static function make(): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function tabs(array | Closure $tabs): static
    {
        $this->childComponents($tabs);

        return $this;
    }

    public function activeTab(int | Closure $activeTab): static
    {
        $this->activeTab = $activeTab;

        return $this;
    }

    public function getActiveTab(): int
    {
        if ($this->isTabPersistedInQueryString()) {
            $queryStringTab = request()->query($this->getTabQueryStringKey());

            foreach ($this->getChildComponentContainer()->getComponents() as $index => $tab) {
                if ($tab->getId() !== $queryStringTab) {
                    continue;
                }

                return $index + 1;
            }
        }

        return $this->evaluate($this->activeTab);
    }

    public function getTabQueryStringKey(): ?string
    {
        return $this->evaluate($this->tabQueryStringKey);
    }

    public function isTabPersistedInQueryString(): bool
    {
        return filled($this->getTabQueryStringKey());
    }

    public function persistTabInQueryString(string | Closure | null $key = 'tab'): static
    {
        $this->tabQueryStringKey = $key;

        return $this;
    }
}
