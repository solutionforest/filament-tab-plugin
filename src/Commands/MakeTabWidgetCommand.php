<?php

namespace SolutionForest\TabLayoutPlugin\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeTabWidgetCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Creates a Filament tab widget class.';

    protected $signature = 'make:filament-tab-widget {name?} {--R|resource=} {--panel=} {--F|force}';

    public function handle(): int
    {
        $widget = Str::of(
            strval($this->argument('name') ?? text(
                label: 'Name',
                placeholder: '(e.g. `MemberDetails`)',
                required: true
            ))
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $widgetClass = (string) Str::of($widget)->afterLast('\\');
        $widgetNamespace = Str::of($widget)->contains('\\') ?
            (string) Str::of($widget)->beforeLast('\\') :
            '';

        $resource = null;
        $resourceClass = null;

        if (class_exists(Resource::class)) {
            $resourceInput = $this->option('resource') ?? text(
                label: 'What is the resource you would like to create this in?',
                placeholder: '[Optional] MemberResource',
            );

            if (filled($resourceInput)) {
                $resource = (string) str($resourceInput)
                    ->studly()
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                if (! str($resource)->endsWith('Resource')) {
                    $resource .= 'Resource';
                }

                $resourceClass = (string) str($resource)
                    ->afterLast('\\');
            }
        }

        $panel = null;

        if (class_exists(Panel::class)) {
            $panel = $this->option('panel');

            if ($panel) {
                $panel = Filament::getPanel($panel, isStrict: false);
            }

            if (! $panel) {
                $panels = Filament::getPanels();
                $namespace = config('livewire.class_namespace');

                /** @var ?Panel $panel */
                $panel = $panels[select(
                    label: 'Where would you like to create this?',
                    options: array_unique([
                        ...array_map(
                            fn (Panel $panel): string => "The [{$panel->getId()}] panel",
                            $panels,
                        ),
                        $namespace => "[{$namespace}] alongside other Livewire components",
                    ])
                )] ?? null;
            }
        }

        $path = null;
        $namespace = null;
        $resourcePath = null;
        $resourceNamespace = null;

        $path = null;
        $namespace = null;
        $resourcePath = null;
        $resourceNamespace = null;

        if (! $panel) {
            $namespace = config('livewire.class_namespace');
            $path = app_path((string) str($namespace)->after('App\\')->replace('\\', '/'));
        } elseif ($resource === null) {
            $widgetDirectories = $panel->getWidgetDirectories();
            $widgetNamespaces = $panel->getWidgetNamespaces();

            foreach ($widgetDirectories as $widgetIndex => $widgetDirectory) {
                if (str($widgetDirectory)->startsWith(base_path('vendor'))) {
                    unset($widgetDirectories[$widgetIndex]);
                    unset($widgetNamespaces[$widgetIndex]);
                }
            }

            $namespace = (count($widgetNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $widgetNamespaces,
                ) :
                (Arr::first($widgetNamespaces) ?? 'App\\Filament\\Widgets');
            $path = (count($widgetDirectories) > 1) ?
                $widgetDirectories[array_search($namespace, $widgetNamespaces)] :
                (Arr::first($widgetDirectories) ?? app_path('Filament/Widgets/'));
        } else {
            $resourceDirectories = $panel->getResourceDirectories();
            $resourceNamespaces = $panel->getResourceNamespaces();

            foreach ($resourceDirectories as $resourceIndex => $resourceDirectory) {
                if (str($resourceDirectory)->startsWith(base_path('vendor'))) {
                    unset($resourceDirectories[$resourceIndex]);
                    unset($resourceNamespaces[$resourceIndex]);
                }
            }

            $resourceNamespace = (count($resourceNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $resourceNamespaces,
                ) :
                (Arr::first($resourceNamespaces) ?? 'App\\Filament\\Resources');
            $resourcePath = (count($resourceDirectories) > 1) ?
                $resourceDirectories[array_search($resourceNamespace, $resourceNamespaces)] :
                (Arr::first($resourceDirectories) ?? app_path('Filament/Resources/'));
        }

        $path = (string) str($widget)
            ->prepend('/')
            ->prepend($resource === null ? $path : "{$resourcePath}\\{$resource}\\Widgets\\")
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        if (! $this->option('force') && $this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $this->copyStubToApp('TabsWidget', $path, [
            'class' => $widgetClass,
            'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
        ]);

        $this->components->info("Filament tab widget [{$path}] created successfully.");

        if ($resource !== null) {
            $this->components->info("Make sure to register the widget in `{$resourceClass}::getWidgets()`, and then again in `getHeaderWidgets()` or `getFooterWidgets()` of any `{$resourceClass}` page.");
        }

        return static::SUCCESS;
    }
}
