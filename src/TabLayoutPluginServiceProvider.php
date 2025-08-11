<?php

namespace SolutionForest\TabLayoutPlugin;

use Livewire\Livewire;
use Livewire\Mechanisms\ComponentRegistry;
use SolutionForest\TabLayoutPlugin\Commands\MakeTabComponent;
use SolutionForest\TabLayoutPlugin\Commands\MakeTabWidgetCommand;
use SolutionForest\TabLayoutPlugin\Livewire\Components\Tabs\LivewireWrapper;
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget;
use SolutionForest\TabLayoutPlugin\Widgets\TabWidget;
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

        Livewire::component(static::$name.'::component-wrapper', LivewireWrapper::class);

        foreach ([
            TabsWidget::class,
            TabWidget::class,
        ] as $widgetFqcn) {
            $componentName = app(ComponentRegistry::class)->getName($widgetFqcn);
            Livewire::component($componentName, $widgetFqcn);
        }
    }
}
