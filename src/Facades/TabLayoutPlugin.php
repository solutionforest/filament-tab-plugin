<?php

namespace SolutionForest\TabLayoutPlugin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SolutionForest\TabLayoutPlugin\TabLayoutPlugin
 */
class TabLayoutPlugin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SolutionForest\TabLayoutPlugin\TabLayoutPlugin::class;
    }
}
