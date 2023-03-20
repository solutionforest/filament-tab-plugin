<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Layouts;

use SolutionForest\TabLayoutPlugin\Components;

trait InteractsWithTab
{
    protected bool $hasMounted = false;

    protected Components\Tabs $tabs;

    public function mountInteractsWithTab(): void
    {
        $this->tabs = $this->getTabs();
    }

    public static function tabs(Components\Tabs $tabs): Components\Tabs
    {
        return $tabs;
    }

    protected function getTabSchema(): array
    {
        return [];
    }

    public function getTabs() : Components\Tabs
    {
        return Components\Tabs::make()
            ->tabs($this->schema());
    }
}
