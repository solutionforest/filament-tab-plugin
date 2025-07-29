<?php

namespace SolutionForest\TabLayoutPlugin;

use SolutionForest\TabLayoutPlugin\Commands\MakeTabWidgetCommand;
use SolutionForest\TabLayoutPlugin\Commands\MakeTabComponent;
use Livewire\Livewire;
use SolutionForest\TabLayoutPlugin\Components\Tabs\ComponentWrapper;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TabLayoutPluginServiceProvider extends PackageServiceProvider
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
            MakeTabWidgetCommand::class,
            MakeTabComponent::class,
        ];
    }

    public function bootingPackage()
    {
        parent::bootingPackage();

        Livewire::component(static::$name.'::component-wrapper', ComponentWrapper::class);
    }
}
