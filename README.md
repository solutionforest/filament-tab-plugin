> [!IMPORTANT]
> We will archive this project since filament3 supports tabs now.
> https://beta.filamentphp.com/docs/3.x/infolists/layout/tabs

# SolutionForest Limited BlackFriday Promotion

![20%_off_Black_friday_promote](https://github.com/solutionforest/Filament-SimpleLightBox/assets/68211972/a724e1eb-d033-490f-823b-3e3155d3b4b3)



- A Event Photo Management Tools [GatherPro.events](https://gatherpro.events/)
  
Promote Code : SFBLACKFRIDAY2023

- [Filament CMS Plugin ](https://filamentphp.com/plugins/solution-forest-cms-website)

Promote Code : SFBLACKFRIDAY2023

# Tab Layout Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/run-tests?label=tests)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/Check%20&%20fix%20styling?label=code%20style)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)

This plugin creates widgets with tab layout for Filament Admin.

![filament-tab-1](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/0dd61497-1c22-474c-b74a-75700df51292)

Demo site : https://filament-cms-website-demo.solutionforest.net/admin

Demo username : demo@solutionforest.net

Demo password : 12345678 Auto Reset every hour.
## Installation

You can install the package via composer:

```bash
composer require solution-forest/tab-layout-plugin
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="tab-layout-plugin-views"
```

## Usage

To build `Tab` widget: 
```php
php artisan make:filament-tab-widget DummyTabs
```

You will then define the child component 'schema()' to display inside:
```php
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabLayoutTab;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabContainer;
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget as BaseWidget;

class DummyTabs extends BaseWidget
{
    protected function schema(): array
    {
        return [
            TabLayoutTab::make('Label 1')
                ->icon('heroicon-o-bell') 
                ->badge('39')
                ->schema([
                    TabContainer::make(\Filament\Widgets\AccountWidget::class)
                ]),
            TabLayoutTab::make('Label 2')
                ->schema([
                    TabContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),
                    TabContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),
                ])
                ->columns(2),
            TabLayoutTab::make('Go To Filamentphp (Link)')->url("https://filamentphp.com/", true),
        ];
    }
}
```

Tabs may have an icon and badge, which you can set using the `icon()` and `badge()` methods:
```php
Tab::make('Label 1')
    ->icon('heroicon-o-bell') 
    ->badge('39')
    ->schema([
        // ...
    ]),
```

## Assign parameters to component
Additionally, you have the option to pass an array of data to your component.
```php
protected function schema(): array
{
    return [
        TabLayoutTab::make('Label 1')
            ->icon('heroicon-o-bell')
            ->badge('39')
            ->schema([
                TabContainer::make(\Filament\Widgets\AccountWidget::class)
                TabContainer::make(ViewProductCategory::class)  //TARGET COMPONENT
                    ->data(['record' => 1]),    // TARGET COMPONENT'S DATA
            ]),
        TabLayoutTab::make('Label 2')
            ->schema([
                TabContainer::make(\Filament\Widgets\FilamentInfoWidget::class),
            ]),
    ];
}
```
![tab-example-1](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/1061acbb-cfdf-422f-8c2f-1c0f709ecf7f)
![tab-example-2](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/23898112-9d25-4260-bed1-081e679b8b68)


In addition to using the `TabContainer` component, you can create your own custom tab layout components by extending the `TabLayoutComponent` class or using command `php artisan tab-layout:component`.

For example, the following PHP code defines a FilamentInfoWidget class that extends TabLayoutComponent and specifies a `ComponentTabComponent` as the tab component to use. The **getData** method can be used to populate the component with data.
```php
<?php

namespace App\Filament\Tabs\Components;

use Filament\Widgets\FilamentInfoWidget as ComponentTabComponent;
use SolutionForest\TabLayoutPlugin\Components\Tabs\TabLayoutComponent;

class FilamentInfoWidget extends TabLayoutComponent
{
    protected ?string $component = ComponentTabComponent::class;

    public function getData(): array
    {
        return [
            // Data to assign to component
        ];
    }
}
```
You can also use the `php artisan tab-layout:component` command to generate the code for a new tab layout component. For example, to generate a `FilamentInfoWidget` component, you can run the following command:
```bash
php artisan tab-layout:component FilamentInfoWidget Filament\Widgets\FilamentInfoWidget
```

After creating your custom tab layout component by extending the `TabLayoutComponent` class, you can register it on the schema of a `TabLayoutTab` instance.
```php
protected function schema(): array
{
    return [
        ...
        TabLayoutTab::make('Label 3')
            ->schema([
                App\Filament\Tabs\Components\FilamentInfoWidget::make()
                    // ->data([]),  // Also can assign data here
            ]),
    ];
}
```


## Changelog

Please see [CHANGELOG](../../releases) for more information on what has changed recently.


## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
