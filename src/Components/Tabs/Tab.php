<?php

namespace SolutionForest\TabLayoutPlugin\Components\Tabs;

use Closure;
use Illuminate\Support\Str;
use SolutionForest\TabLayoutPlugin\Components\FilamentComponent;
use SolutionForest\TabLayoutPlugin\Concerns\Components\BelongsToContainer;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasBadge;
use SolutionForest\TabLayoutPlugin\Concerns\Components\HasIcon;

class Tab extends FilamentComponent
{
    use BelongsToContainer;
    use HasBadge;
    use HasIcon;

    protected string $view = 'tab-layout-plugin::components.tabs.tab';

    protected bool $shouldOpenUrlInNewTab = false;

    protected string|Closure|null $url = null;

    protected ?string $tabId = null;

    final public function __construct(string $label, ?string $id = null)
    {
        $this->label($label);
        $this->tabId(Str::slug($id ?: $label));
    }

    public static function make(string $label, ?string $id = null): static
    {
        $static = app(static::class, ['label' => $label, 'id' => $id]);
        $static->configure();

        return $static;
    }

    public function tabId(string $tabId): static
    {
        $this->tabId = $tabId;

        return $this;
    }

    public function url(string|Closure|null $url, bool $shouldOpenInNewTab = false): static
    {
        $this->openUrlInNewTab($shouldOpenInNewTab);
        $this->url = $url;

        return $this;
    }

    public function openUrlInNewTab(bool $condition = true): static
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    public function getId(): string
    {
        return $this->getContainer()->getParentComponent()->getId().'-'.$this->getTabId().'-tab';
    }

    public function getTabId(): string
    {
        return $this->tabId;
    }

    public function getColumnsConfig(): array
    {
        return $this->columns ?? $this->getContainer()->getColumnsConfig();
    }

    public function getUrl(): ?string
    {
        return value($this->url);
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return $this->shouldOpenUrlInNewTab;
    }

    public function canConcealComponents(): bool
    {
        return true;
    }
}
