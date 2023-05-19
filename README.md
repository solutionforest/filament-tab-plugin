# Tab Layout Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/run-tests?label=tests)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/Check%20&%20fix%20styling?label=code%20style)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)

This plugin creates widgets with tab layout for Filament Admin.

![filament-tab-1](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/0dd61497-1c22-474c-b74a-75700df51292)

Demo site : https://filament-cms-website-demo.solutionforest.net/

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
                    // ...
                ]),
            TabLayoutTab::make('Label 2')
                ->schema([
                    // ...
                ]),
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
> **Tip: Ensure that the index or key of the `schemaComponentData` array matches the component's index or key. This is important for proper rendering and functioning of the component. !!!**
```php
protected function schema(): array
{
    return [

        // SAMPLE CODE, CAN DELETE
        TabLayoutTab::make('Label 1')
            ->icon('heroicon-o-bell')
            ->badge('39')
            ->schema([
                new \Filament\Widgets\AccountWidget,
                new \App\Filament\Resources\ProductCategoryResource\Pages\ViewProductCategory() // TARGET COMPONENT
            ])
            ->schemaComponentData([
                null,
                ['record' => 1 ]    // TARGET COMPONENT'S DATA
            ]),
        TabLayoutTab::make('Label 2')
            ->schema([
                new \Filament\Widgets\FilamentInfoWidget(),
            ]),

    ];
}
```
![tab-example-1](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/1061acbb-cfdf-422f-8c2f-1c0f709ecf7f)
![tab-example-2](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/23898112-9d25-4260-bed1-081e679b8b68)


## Changelog

Please see [CHANGELOG](../../releases) for more information on what has changed recently.


## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
