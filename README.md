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


## Supported Filament versions
| Filament Version | Plugin Version |
|------------------|----------------|
| v2               | <=1.x.x |
| v3               | 2.x.x          |
| v4               | 3.x.x-beta    |

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

### Tab widget

To build `Tab` widget: 
```php
php artisan make:filament-tab-widget DummyTabs
```

You will then define the child component 'schema()' to display inside:
```php

namespace App\Filament\Widgets;

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
                    // Display livewire component
                    TabContainer::make(\Filament\Widgets\AccountWidget::class),
                    // Display html
                    str('
## This is a dummy html code inside tab

- This is a bullet point
- Another bullet point
```php
echo "This is a code block";
```')->markdown()->toHtmlString(),
                ]),
            TabLayoutTab::make('Label 2')
                ->schema([
                    // Display raw string
                    'Raw string here',

                    // Display livewire 
                    app(\App\Livewire\Dummy::class, ['__id' => uniqid() . '-dummy']),

                    // Display livewire with filling data
                    TabContainer::make(\App\Filament\Resources\UserResource\Pages\EditUser::class)  //TARGET COMPONENT
                        ->data(['record' => 1]),    // TARGET COMPONENT'S DATA

                    TabContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),
                    TabContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),
                ])
                ->columns(2),
            // Hyper link
            TabLayoutTab::make('Go To Filamentphp (Link)')->url("https://filamentphp.com/", true),
        ];
    }
}
```

#### Customize the icon and badge

Tabs may have an icon and badge, which you can set using the `icon()` and `badge()` methods:
```php
Tab::make('Label 1')
    ->icon('heroicon-o-bell') 
    ->badge('39')
    ->schema([
        // ...
    ]),
```

#### Assign parameters to component
Additionally, you have the option to pass an array of data to your component.
```php
protected function schema(): array
{
    return [
        TabLayoutTab::make('Label 1')
            ->icon('heroicon-o-bell')
            ->badge('39')
            ->schema([
                TabContainer::make(\Filament\Widgets\AccountWidget::class),
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


Then, add the tab widget to your page, e.g. 
```php
// on App\Resources\UserResource\ListUsers.php

class ListUsers extends ListRecords
{
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DummyTabs::class,
        ];
    }
}
```

#### Make a own tab container

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

### Create a dynamic tab widget

You can render multiple livewire components inside a tab widget by using the `TabsWidget::make()` method:

```php
// on App\Resources\UserResource\ListUsers.php

use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget;
use SolutionForest\TabLayoutPlugin\Widgets\TabWidgetContentConfiguration;

class ListUsers extends ListRecords
{
    protected function getHeaderWidgets(): array
    {
        return [
            TabsWidget::make([
                // Method 1: Using TabWidgetContentConfiguration object
                new TabWidgetContentConfiguration(
                    component: \Filament\Widgets\AccountWidget::class,
                    params: [],
                    tabKey: 'account_widget',
                    tabLabel: 'Account Widget',
                ),
                
                // Method 2: Using array syntax
                [
                    'component' => \App\Filament\Resources\UserResource\Pages\EditUser::class,
                    'params' => ['record' => 1], // Pass parameters to livewire component
                    'tabKey' => 'edit_user',
                    'tabLabel' => 'Edit User',
                ]
            ])
            // Method 3: Using the tab() method to add additional tabs
            ->tab(
                new TabWidgetContentConfiguration(
                    component: \Filament\Widgets\FilamentInfoWidget::class,
                    params: [],
                    tabKey: 'filament_info_widget',
                    tabLabel: 'Filament Info Widget',
                ),
            ),
        ];
    }
}
```

This approach gives you three ways to configure tabs:
1. **TabWidgetContentConfiguration object** - Most explicit and type-safe
2. **Array syntax** - Simpler for basic configurations
3. **Chain tab() method** - Useful for adding tabs conditionally

## Changelog

Please see [CHANGELOG](../../releases) for more information on what has changed recently.


## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
