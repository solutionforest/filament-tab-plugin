<?php

namespace SolutionForest\TabLayoutPlugin\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class MakeTabWidgetCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Creates a Filament tab widget class.';

    protected $signature = 'make:filament-tab-widget {name?} {--F|force}';

    public function handle(): int
    {
        $path = config('filament.widgets.path', app_path('Filament/Widgets/'));
        $namespace = config('filament.widgets.namespace', 'App\\Filament\\Widgets');

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

        $path = (string) Str::of($widget)
            ->prepend('/')
            ->prepend($path)
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        if (! $this->option('force') && $this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $this->copyStubToApp('TabsWidget', $path, [
            'class' => $widgetClass,
            'namespace' => $namespace.($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
        ]);

        $this->info("Successfully created {$widget}!");

        return static::SUCCESS;
    }
}
