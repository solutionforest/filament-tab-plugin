<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Layouts;

use SolutionForest\TabLayoutPlugin\Components\Tabs;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabContainer;
use SolutionForest\TabLayoutPlugin\Contracts\HasTabs;
use SolutionForest\TabLayoutPlugin\Widgets\TabWidgetContentConfiguration;

trait InteractsWithTab
{
    protected bool $hasMounted = false;

    protected Tabs $tabs;

    public array $tabComponents = [];

    public function mountInteractsWithTab(): void
    {
        $this->tabs = static::tabs($this->getTabs());
        if ($this instanceof HasTabs) {
            $this->tabs->livewire($this);
        }
    }

    public static function tabs(Tabs $tabs): Tabs
    {
        return $tabs;
    }

    protected function getTabSchema(): array
    {
        return [];
    }

    public function tab(array|Tab $tab): static
    {
        $this->tabComponents[] = $tab;

        return $this;
    }

    public function getTabs(): Tabs
    {
        $id = method_exists($this, 'getId') ? $this->getId() : uniqid();

        return Tabs::make($id)
            ->tabs(function () {
                $tabs = $this->convertTabComponents($this->tabComponents);
                if (method_exists($this, 'schema') && ($schema = $this->schema()) && is_array($schema)) {
                    $tabs = array_merge($tabs, $this->convertTabComponents($schema));
                }
                if (($tabSchema = $this->getTabSchema()) && is_array($tabSchema)) {
                    $tabs = array_merge($tabs, $this->convertTabComponents($tabSchema));
                }

                return $tabs;
            });
    }

    protected function convertTabComponents($tabs): array
    {
        $convertedTabs = [];
        foreach ($tabs as $tab) {

            if (is_array($tab) && TabWidgetContentConfiguration::isValidArray($tab)) {
                $tabConfig = TabWidgetContentConfiguration::parseFormArray($tab);
                $tab = Tab::make($tabConfig->tabLabel, $tabConfig->tabKey)
                    ->schema([
                        TabContainer::make($tabConfig->component)
                            ->data($tabConfig->params),
                    ]);
            } elseif ($tab instanceof Tab) {
                //
            } else {
                throw new \InvalidArgumentException('Each tab must be an instance of '.Tab::class.' or a valid array configuration.');
            }
            $convertedTabs[] = $tab;
        }

        return $convertedTabs;
    }
}
