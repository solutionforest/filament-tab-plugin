<?php

namespace SolutionForest\TabLayoutPlugin\Contracts;

use SolutionForest\TabLayoutPlugin\Components\Tabs;

interface HasTabs
{
    public function getTabs(): Tabs;
}
