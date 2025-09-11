<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Layouts;

use SolutionForest\TabLayoutPlugin\Components\Tabs;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab;
use SolutionForest\TabLayoutPlugin\Contracts\HasTabs;
use SolutionForest\TabLayoutPlugin\Schemas\Components\LivewireContainer;
use SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema;
use SolutionForest\TabLayoutPlugin\Widgets\TabWidgetContentConfiguration;

trait InteractsWithTab
{
    protected bool $hasMounted = false;

    protected Tabs $tabs;

    /**
     * @var array<string, mixed>|SimpleTabSchema[]|Tab[]
     */
    public array $tabComponents = [];

    public function mountInteractsWithTab(): void
    {
        $this->generateTabs();
    }

    private function generateTabs(): void
    {
        $this->tabs = static::tabs($this->getTabs());
        if ($this instanceof HasTabs) {
            $this->tabs->livewire($this);
        }
    }

    public function rendering(): void
    {
        $this->generateTabs();
    }

    public static function tabs(Tabs $tabs): Tabs
    {
        return $tabs;
    }

    protected function getTabSchema(): array
    {
        return [];
    }

    public function tab(array|Tab|SimpleTabSchema $tab): static
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

            if (is_array($tab)) {
                if (! SimpleTabSchema::isValidArray($tab) && TabWidgetContentConfiguration::isValidArray($tab)) {
                    $tab = TabWidgetContentConfiguration::parseFormArray($tab);
                } else {
                    $tab = SimpleTabSchema::parseFormArray($tab);
                }
            }

            if ($tab instanceof SimpleTabSchema) {

                $tmpTab = Tab::make($tab->label, $tab->id);

                switch ($tab->contentType) {
                    case 'url':
                        $tmpTab->url($tab->content, $tab->contentParams['shouldOpenInNewTab'] ?? false);
                        break;
                    case 'livewire':
                    default:
                        // Livewire
                        if ($tab->content) {
                            $tmpTab->schema([
                                LivewireContainer::make($tab->content)->data($tab->contentParams ?? []),
                            ]);
                        }
                }

                if ($tab->icon) {
                    $tmpTab->icon($tab->icon);
                }

                if ($tab->badge) {
                    $tmpTab->badge($tab->badge);
                }

                $tab = $tmpTab;

            } elseif ($tab instanceof Tab) {

                // If the tab is already a Tab instance, we can use it directly

            } else {
                throw new \InvalidArgumentException('Each tab must be an instance of '.Tab::class.' or a valid array configuration.');
            }

            $convertedTabs[] = $tab;
        }

        return $convertedTabs;
    }
}
