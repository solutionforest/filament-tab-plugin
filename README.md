# Tab Layout Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/run-tests?label=tests)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/solution-forest/tab-layout-plugin/Check%20&%20fix%20styling?label=code%20style)](https://github.com/solution-forest/tab-layout-plugin/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)

This plugin creates widgets with tab layout for Filament Admin.

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
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget as BaseWidget;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab;

class DummyTabs extends BaseWidget
{
    protected function schema(): array
    {
        return [
            Tab::make('Label 1')
            ->icon('heroicon-o-bell') 
            ->badge('39')
                ->schema([
                    // ...
                ]),
            Tab::make('Label 2')
                ->schema([
                    // ...
                ]),
        ];
    }
}
```

Tabs may have an icon and badge, which you can set using the icon() and badge() methods:
```php
    Tab::make('Label 1')
    ->icon('heroicon-o-bell') 
    ->badge('39')
    ->schema([
        // ...
    ]),
```


## Changelog

Please see [CHANGELOG](../../releases) for more information on what has changed recently.


## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
