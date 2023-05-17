<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Layouts;

use SolutionForest\TabLayoutPlugin\Components\Tabs;

trait InteractsWithTab
{
    protected bool $hasMounted = false;

    protected Tabs $tabs;

    public function mountInteractsWithTab(): void
    {
        $this->tabs = $this->getTabs();
    }

    public static function tabs(Tabs $tabs): Tabs
    {
        return $tabs;
    }

    protected function getTabSchema(): array
    {
        return [];
    }

    public function getTabs(): Tabs
    {
        return Tabs::make()
            ->tabs($this->schema());
    }
}
