<?php

namespace SolutionForest\TabLayoutPlugin;

use Livewire\Livewire;
use SolutionForest\TabLayoutPlugin\Livewire\Components\Tabs\LivewireWrapper;
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
            Commands\MakeTabWidgetCommand::class,
            Commands\MakeTabComponent::class,
        ];
    }

    public function bootingPackage()
    {
        parent::bootingPackage();

        Livewire::component(static::$name.'::component-wrapper', LivewireWrapper::class);
    }
}
