<?php

namespace SolutionForest\TabLayoutPlugin\Components;

use Closure;
use Filament\Support\Concerns\CanBeContained;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Livewire\Component;
use RuntimeException;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab;
use SolutionForest\TabLayoutPlugin\Contracts\HasTabs;

class Tabs extends FilamentComponent
{
    use CanBeContained;
    use HasExtraAlpineAttributes;

    protected string $view = 'tab-layout-plugin::components.tabs';

    public int|Closure $activeTab = 1;

    protected string|Closure|null $tabQueryStringKey = null;

    protected ?HasTabs $livewire = null;

    public function __construct(?string $id = null)
    {
        $this->id($id ?? uniqid());
    }

    public static function make(?string $id = null): static
    {
        $static = app(static::class, ['id' => $id]);

        if (! $static instanceof static) {
            throw new RuntimeException(static::class.' could not be resolved from the container.');
        }

        $static->configure();

        return $static;
    }

    public function livewire(HasTabs $livewire): static
    {
        $this->livewire = $livewire;

        return $this;
    }

    /**
     * @param  array<int|string, mixed>|Closure  $tabs
     */
    public function tabs(array|Closure $tabs): static
    {
        $this->childComponents($tabs);

        return $this;
    }

    public function activeTab(int|Closure $activeTab): static
    {
        $this->activeTab = $activeTab;

        return $this;
    }

    public function getLivewire(): ?HasTabs
    {
        return $this->livewire;
    }

    public function getActiveTab(): int
    {
        if ($this->isTabPersistedInQueryString()) {

            // $queryStringTab = request()->query($this->getTabQueryStringKey());
            $tabQueryStringKey = $this->getTabQueryStringKey();
            $queryStringTab = null;
            try {
                if (
                    filled($tabQueryStringKey) &&
                    ($livewire = $this->getLivewire()) &&
                    ($livewire instanceof Component) &&
                    (property_exists($livewire, $tabQueryStringKey))
                ) {
                    $queryStringTab = $livewire->{$tabQueryStringKey};
                }
            } catch (\Throwable $th) {
                // Skip
            }

            foreach ($this->getChildComponentContainer()->getComponents() as $index => $tab) {

                if (! $tab instanceof Tab) {
                    continue;
                }

                if ($tab->getId() !== $queryStringTab) {
                    continue;
                }

                return (int) $index + 1;
            }
        }

        $activeTab = $this->evaluate($this->activeTab);

        return is_int($activeTab) ? $activeTab : 1;
    }

    public function getTabQueryStringKey(): ?string
    {
        $key = $this->evaluate($this->tabQueryStringKey);

        return is_string($key) ? $key : null;
    }

    public function isTabPersistedInQueryString(): bool
    {
        return filled($this->getTabQueryStringKey());
    }

    public function persistTabInQueryString(string|Closure|null $key = 'tab'): static
    {
        $this->tabQueryStringKey = $key;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        return match ($parameterName) {
            'livewire' => [$this->getLivewire()],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }
}
