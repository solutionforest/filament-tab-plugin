<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Layouts;

use Livewire\Component;
use SolutionForest\TabLayoutPlugin\Components\Tabs;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab;
use SolutionForest\TabLayoutPlugin\Contracts\HasTabs;
use SolutionForest\TabLayoutPlugin\Schemas\Components\LivewireContainer;
use SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema;

/**
 * @phpstan-require-extends Component
 */
trait InteractsWithTab
{
    protected bool $hasMounted = false;

    protected Tabs $tabs;

    /**
     * @var array<int, array<string, mixed>|SimpleTabSchema|Tab>
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

    /**
     * @return array<int, array<string, mixed>|Tab|SimpleTabSchema>
     */
    protected function getTabSchema(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>|Tab|SimpleTabSchema  $tab
     */
    public function tab(array|Tab|SimpleTabSchema $tab): static
    {
        $this->tabComponents[] = $tab;

        return $this;
    }

    public function getTabs(): Tabs
    {
        $id = $this->getId();

        return Tabs::make(is_string($id) ? $id : uniqid())
            ->tabs(function (): array {
                $tabs = $this->convertTabComponents($this->tabComponents);
                if (method_exists($this, 'schema')) {
                    $schema = $this->schema();
                    if (is_array($schema) && $schema !== []) {
                        $tabs = array_merge($tabs, $this->convertTabComponents($schema));
                    }
                }

                $tabSchema = $this->getTabSchema();
                if ($tabSchema !== []) {
                    $tabs = array_merge($tabs, $this->convertTabComponents($tabSchema));
                }

                return $tabs;
            });
    }

    /**
     * @param  array<int|string, mixed>  $tabs
     * @return array<int, Tab>
     */
    protected function convertTabComponents(array $tabs): array
    {
        $convertedTabs = [];
        foreach ($tabs as $tab) {

            if (is_array($tab)) {
                $tab = SimpleTabSchema::parseFormArray(self::normalizeSimpleTabSchemaArray(self::stringKeyedArray($tab)));
            }

            if ($tab instanceof SimpleTabSchema) {

                $tmpTab = Tab::make($tab->label, $tab->id);

                switch ($tab->contentType) {
                    case 'url':
                        $shouldOpenInNewTab = $tab->contentParams['shouldOpenInNewTab'] ?? false;
                        $tmpTab->url($tab->content, $shouldOpenInNewTab === true);
                        break;
                    case 'livewire':
                    default:
                        // Livewire
                        if ($tab->content) {
                            $tmpTab->schema([
                                LivewireContainer::make($tab->content)->data($tab->contentParams),
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

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private static function normalizeSimpleTabSchemaArray(array $item): array
    {
        if (SimpleTabSchema::isValidArray($item)) {
            return $item;
        }

        foreach ([
            'id' => ['tabKey', 'key'],
            'label' => ['tabLabel'],
            'content' => ['component'],
            'contentParams' => ['params'],
        ] as $newParamKey => $oldParamKeys) {
            foreach ($oldParamKeys as $oldParamKey) {
                if (isset($item[$oldParamKey])) {
                    $item[$newParamKey] = $item[$oldParamKey];
                    unset($item[$oldParamKey]);
                }
            }
        }

        return $item;
    }

    /**
     * @param  array<int|string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function stringKeyedArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
