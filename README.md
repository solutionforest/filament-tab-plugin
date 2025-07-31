# Tab Layout Plugin

![Filament Supported Versions](https://img.shields.io/badge/filament-^3.3-green.svg)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/tab-layout-plugin.svg?style=flat-square)](https://packagist.org/packages/solution-forest/tab-layout-plugin)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/solutionforest/tab-layout-plugin/run-tests?label=tests)](https://github.com/solutionforest/tab-layout-plugin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/solutionforest/tab-layout-plugin/Check%20&%20fix%20styling?label=code%20style)](https://github.com/solutionforest/tab-layout-plugin/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
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

### Create a Simple Tab Widget

Create tabbed interfaces with individual Livewire components using the TabsWidget::make() method. This is the quickest way to get started with basic tab functionality.

```php
// In App\Resources\UserResource\ListUsers.php

use SolutionForest\TabLayoutPlugin\Schemas\SimpleTabSchema;
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget;

class ListUsers extends ListRecords
{
    protected function getHeaderWidgets(): array
    {
        return [
            TabsWidget::make([

                SimpleTabSchema::make(
                    label: 'Account Widget',
                    id: 'account_widget',
                )->livewireComponent(\Filament\Widgets\AccountWidget::class),

                SimpleTabSchema::make(
                    label: 'Edit User',
                )->livewireComponent(\App\Filament\Resources\UserResource\Pages\EditUser::class, ['record' => 1]),

                SimpleTabSchema::make('Link')
                    ->url('https://example.com', true)
                    ->icon('heroicon-o-globe-alt'),
            ]),
        ];
    }
}
```

**This approach provides three ways to configure tabs:**
1. **TabWidgetContentConfiguration object** - Most explicit and type-safe
2. **Array syntax** - Simpler for basic configurations
3. **Chain tab() method** - Useful for adding tabs conditionally

### Create a Complex Tab Widget

You can also create multiple Livewire components, HTML, and strings inside each tab. You can even make a tab act as a redirect link by extending the `TabsWidget` class.

**To generate a Tab widget:**
```bash
php artisan make:filament-tab-widget DummyTabs
```

You will then define the child components in the `schema()` method to display inside:
```php

namespace App\Filament\Widgets;

use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabLayoutTab;
use SolutionForest\TabLayoutPlugin\Schemas\Components\LivewireContainer;
use SolutionForest\TabLayoutPlugin\Schemas\Components\TabContentContainer;
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

                    // Display Livewire component
                    LivewireContainer::make(\Filament\Widgets\AccountWidget::class),

                    // Display HTML
                    str('
## This is dummy HTML code inside a tab

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

                    // Display Livewire component
                    app(\App\Livewire\Dummy::class, ['__id' => uniqid() . '-dummy']),

                    // Display Livewire component with data
                    LivewireContainer::make(\App\Filament\Resources\UserResource\Pages\EditUser::class)
                        ->data(['record' => 1]),

                    LivewireContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),

                    LivewireContainer::make(\Filament\Widgets\AccountWidget::class)
                        ->columnSpan(1),
                ])
                ->columns(2),
                
            // External link
            TabLayoutTab::make('Go To FilamentPHP (Link)')
                ->url("https://filamentphp.com/", true),
        ];
    }
}
```

#### Customize the Icon and Badge

Tabs may have an icon and badge, which you can set using the `icon()` and `badge()` methods:
```php
TabLayoutTab::make('Label 1')
    ->icon('heroicon-o-bell') 
    ->badge('39')
    ->schema([
        // ...
    ]),
```

#### Assign Parameters to Components
Additionally, you have the option to pass an array of data to your component.
```php
protected function schema(): array
{
    return [
        TabLayoutTab::make('Label 1')
            ->icon('heroicon-o-bell')
            ->badge('39')
            ->schema([
                LivewireContainer::make(\Filament\Widgets\AccountWidget::class),
                
                // Display Livewire component with data
                LivewireContainer::make(ViewProductCategory::class)
                    // The Data of target component
                    ->data(['record' => 1]),    
            ]),

        TabLayoutTab::make('Label 2')
            ->schema([
                LivewireContainer::make(\Filament\Widgets\FilamentInfoWidget::class),
            ]),
    ];
}
```
![tab-example-1](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/1061acbb-cfdf-422f-8c2f-1c0f709ecf7f)
![tab-example-2](https://github.com/solutionforest/filament-tab-plugin/assets/68525320/23898112-9d25-4260-bed1-081e679b8b68)


Then, add the tab widget to your page, e.g.:
```php
// In App\Resources\UserResource\ListUsers.php

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

#### Set Default Active Tab

Control which tab is active when the widget loads. You can set this either dynamically with a callback or with a static tab order.

```php
use SolutionForest\TabLayoutPlugin\Components\Tabs;
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget as BaseWidget;

class DummyTabs extends BaseWidget
{
    public static function tabs(Tabs $tabs): Tabs
    {
        return $tabs
            // Dynamic: Use a callback to determine the active tab 
            ->activeTab(function (self $livewire, Tabs $component): int {
                return 2; // Second tab will be active
            })
            // Static: Set a specific tab as active by order
            ->activeTab(2); // Second tab will be active
    }
}
```

#### Persist Active Tab in URL

Keep the selected tab active when users reload the page or share URLs by persisting the tab state in the query string.

```php
use SolutionForest\TabLayoutPlugin\Components\Tabs;
use SolutionForest\TabLayoutPlugin\Components\Tabs\Tab as TabLayoutTab;
use SolutionForest\TabLayoutPlugin\Widgets\TabsWidget as BaseWidget;

class DummyTabs extends BaseWidget
{
    // Define the property that will store the active tab
    public $activeTab = '';

    // Enable Livewire query string binding
    public function queryString()
    {
        return ['activeTab'];
    }

    public static function tabs(Tabs $tabs): Tabs
    {
        return $tabs
            ->id('dummy-tabs') // Required: unique ID for the tab group
            // Dynamic: Use a callback to get the query parameter name
            ->persistTabInQueryString(function ($component, $livewire) {
                return 'activeTab'; // Property name to sync with URL
            })
            // Static: Direct property name for URL persistence
            ->persistTabInQueryString('activeTab');
    }
    
    protected function schema(): array
    {
        return [
            TabLayoutTab::make(label: 'Tab 1', id: 'sample-1')
                ->schema([
                    // Tab 1 content...
                ]),
            TabLayoutTab::make(label: 'Tab 2', id: 'sample-2')
                ->schema([
                    // Tab 2 content...
                ]),
        ];
    }
}
```

> **Note:** When using URL persistence, each tab must have a unique `id` and the tab group needs an `id` attribute.

#### Create Your Own Tab Container

In addition to using the `LivewireContainer` component, you can create your own custom tab layout components by extending the `TabLayoutComponent` class or using the `php artisan tab-layout:component` command.

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
                \App\Filament\Tabs\Components\FilamentInfoWidget::make()
                    // ->data([]),  // Also can assign data here
            ]),
    ];
}
```



## Changelog

Please see [CHANGELOG](./CHANGELOG.md) for more information on what has changed recently.


## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
