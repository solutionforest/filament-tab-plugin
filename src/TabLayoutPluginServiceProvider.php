<?php

namespace SolutionForest\TabLayoutPlugin;

use Filament\PluginServiceProvider;
use SolutionForest\TabLayoutPlugin\Commands;
use SolutionForest\TabLayoutPlugin\Components;
use SolutionForest\TabLayoutPlugin\Pages;
use Spatie\LaravelPackageTools\Package;

class TabLayoutPluginServiceProvider extends PluginServiceProvider
{
    public static string $name = 'tab-layout-plugin';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasCommands($this->getCommands())
            ->hasViews();
    }

    protected function getCommands(): array
    {
        return [
            Commands\MakeTabWidgetCommand::class,
            Commands\MakeTabComponent::class,
        ];
    }
}
