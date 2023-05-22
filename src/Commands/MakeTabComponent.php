<?php

namespace SolutionForest\TabLayoutPlugin\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Filament\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeTabComponent extends Command
{
    use CanManipulateFiles;
    use CanValidateInput;

    protected $description = 'Creates a Filament tab component class.';

    protected $signature = 'tab-layout:component {name?} {component?} {--F|force}';

    public function handle(): int
    {
        $path = config('tab-layout-plugin.component.path', app_path('Filament/Tabs/Components'));
        $namespace = config('tab-layout-plugin.component.namespace', 'App\\Filament\\Tabs\\Components');

        $name = (string) Str::of(strval($this->argument('name') ?? $this->askRequired('Name (e.g. `EditProductCategoryPage`)', 'name')))
            ->studly()
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        
        $path = (string) Str::of($name)
            ->prepend('/')
            ->replace('\\', '/')
            ->replace('//', '/')
            ->prepend($path)
            ->append('.php');
        
        $class = (string) Str::of($name)
            ->prepend('\\')
            ->prepend($namespace);

        $classNamespace = Str::beforeLast($class, '\\');
        $className = Str::afterLast($class, '\\');

        $component = (string) Str::of(strval($this->argument('component') ?? $this->askRequired('Component (e.g. `App\Filament\Resources\ProductCategoryResource\Pages\EditProductCategory`)', 'component')))
            ->replace('/', '\\');

        if (! $this->option('force') && $this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $componentName = Str::afterLast($component, '\\');

        $this->copyStubToApp('TabComponent', $path, [
            'class' => $className,
            'namespace' => $classNamespace,
            'componentClass' => $componentName == $className ? 'ComponentTabComponent' : $componentName,
            'component' => $componentName == $className ? "{$component} as ComponentTabComponent" : $component,
        ]);

        $this->info("Successfully created {$className} ! ");

        $this->info("Make sure to register the component in `schema()` of any SolutionForest\\TabLayoutPlugin\\Components\\Tabs\\Tab.");
        return static::SUCCESS;
    }
}
