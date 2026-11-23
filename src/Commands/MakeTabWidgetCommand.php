<?php

namespace SolutionForest\TabLayoutPlugin\Commands;

use Filament\Support\Commands\Concerns\CanAskForLivewireComponentLocation;
use Filament\Support\Commands\Concerns\CanAskForResource;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Filament\Support\Commands\Concerns\HasCluster;
use Filament\Support\Commands\Concerns\HasPanel;
use Filament\Support\Commands\Concerns\HasResourcesLocation;
use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Filament\Support\Commands\FileGenerators\Concerns\CanCheckFileGenerationFlags;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\text;

#[AsCommand(
    name: 'make:filament-tab-widget',
    description: 'Creates a Filament tab widget class.'
)]
class MakeTabWidgetCommand extends Command
{
    use CanAskForLivewireComponentLocation;
    use CanAskForResource;
    use CanCheckFileGenerationFlags;
    use CanManipulateFiles;
    use HasCluster;
    use HasPanel;
    use HasResourcesLocation;

    /**
     * @var class-string
     */
    protected string $fqn;

    protected string $fqnEnd;

    protected bool $hasResource;

    /**
     * @var ?class-string
     */
    protected ?string $resourceFqn = null;

    protected string $widgetsNamespace;

    protected string $widgetsDirectory;

    /**
     * @return array<InputArgument>
     */
    protected function getArguments(): array
    {
        return [
            new InputArgument(
                name: 'name',
                mode: InputArgument::OPTIONAL,
                description: 'The name of the widget to generate, optionally prefixed with directories',
            ),
        ];
    }

    /**
     * @return array<InputOption>
     */
    protected function getOptions(): array
    {
        return [
            new InputOption(
                name: 'cluster',
                shortcut: null,
                mode: InputOption::VALUE_OPTIONAL,
                description: 'The cluster that the resource belongs to',
            ),
            new InputOption(
                name: 'panel',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'The panel to create the widget in',
            ),
            new InputOption(
                name: 'resource',
                shortcut: 'R',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'The resource to create the widget in',
            ),
            new InputOption(
                name: 'resource-namespace',
                shortcut: null,
                mode: InputOption::VALUE_OPTIONAL,
                description: 'The namespace of the resource class, such as ['.app()->getNamespace().'Filament\\Resources]',
            ),
            new InputOption(
                name: 'force',
                shortcut: 'F',
                mode: InputOption::VALUE_NONE,
                description: 'Overwrite the contents of the files if they already exist',
            ),
        ];
    }

    public function handle(): int
    {
        try {
            $this->configureFqnEnd();
            $this->configurePanel(
                question: 'Which panel would you like to create this widget in?',
                initialQuestion: 'Would you like to create this widget in a panel?',
            );
            $this->configureHasResource();
            $this->configureCluster();
            $this->configureResource();
            $this->configureWidgetsLocation();

            $this->configureLocation();

            $this->createTabWidget();
        } catch (FailureCommandOutput) {
            return self::FAILURE;
        }

        $this->components->info("Filament widget [{$this->fqn}] created successfully.");

        if (filled($this->resourceFqn)) {
            $this->components->info("Make sure to register the widget in [{$this->resourceFqn}::getWidgets()], and add it to a page in the resource.");
        } elseif ($this->panel && empty($this->panel->getWidgetNamespaces())) {
            $this->components->info('Make sure to register the widget with [widgets()] or discover it with [discoverWidgets()] in the panel service provider.');
        }

        return self::SUCCESS;
    }

    protected function configureFqnEnd(): void
    {
        $nameArgument = $this->argument('name');
        $nameInput = is_string($nameArgument) && $nameArgument !== ''
            ? $nameArgument
            : text(
                label: 'What is the widget name?',
                placeholder: '(e.g. `MemberDetails`)',
                required: true,
            );

        $this->fqnEnd = (string) str($nameInput)
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');
    }

    protected function configureHasResource(): void
    {
        if (! $this->panel) {
            $this->hasResource = false;

            return;
        }

        $this->hasResource = $this->option('resource') || confirm(
            label: 'Would you like to create this widget in a resource?',
            default: false,
        );
    }

    protected function configureCluster(): void
    {
        if (! $this->hasResource) {
            return;
        }

        $this->configureClusterFqn(
            initialQuestion: 'Is the resource in a cluster?',
            question: 'Which cluster is the resource in?',
        );

        if (blank($this->clusterFqn)) {
            return;
        }

        $this->configureClusterResourcesLocation();
    }

    protected function configureResource(): void
    {
        if (! $this->hasResource) {
            return;
        }

        $this->configureResourcesLocation(question: 'Which namespace would you like to search for resources in?');

        $resourceOption = $this->option('resource');

        $this->resourceFqn = $this->askForResource(
            question: 'Which resource would you like to create this widget in?',
            initialResource: is_string($resourceOption) ? $resourceOption : null,
        );

        $pluralResourceBasenameBeforeResource = (string) str($this->resourceFqn)
            ->classBasename()
            ->beforeLast('Resource')
            ->plural();

        $resourceNamespacePartBeforeBasename = (string) str($this->resourceFqn)
            ->beforeLast('\\')
            ->classBasename();

        $resourceFileName = (new ReflectionClass($this->resourceFqn))->getFileName();

        if ($resourceFileName === false) {
            throw new FailureCommandOutput;
        }

        if ($pluralResourceBasenameBeforeResource === $resourceNamespacePartBeforeBasename) {
            $this->widgetsNamespace = (string) str($this->resourceFqn)
                ->beforeLast('\\')
                ->append('\\Widgets');
            $this->widgetsDirectory = (string) str($resourceFileName)
                ->beforeLast(DIRECTORY_SEPARATOR)
                ->append('/Widgets');

            return;
        }

        $this->widgetsNamespace = "{$this->resourceFqn}\\Widgets";
        $this->widgetsDirectory = (string) str($resourceFileName)
            ->beforeLast('.')
            ->append('/Widgets');
    }

    protected function configureWidgetsLocation(): void
    {
        if (filled($this->resourceFqn)) {
            return;
        }

        if (! $this->panel) {
            [
                $this->widgetsNamespace,
                $this->widgetsDirectory,
            ] = $this->askForLivewireComponentLocation(
                question: 'Where would you like to create the widget?',
            );

            return;
        }

        $directories = $this->panel->getWidgetDirectories();
        $namespaces = $this->panel->getWidgetNamespaces();

        foreach ($directories as $index => $directory) {
            if (str($directory)->startsWith(base_path('vendor'))) {
                unset($directories[$index]);
                unset($namespaces[$index]);
            }
        }

        if (count($namespaces) < 2) {
            $this->widgetsNamespace = (Arr::first($namespaces) ?? app()->getNamespace().'Filament\\Widgets');
            $this->widgetsDirectory = (Arr::first($directories) ?? app_path('Filament/Widgets/'));

            return;
        }

        $keyedNamespaces = array_combine(
            $namespaces,
            $namespaces,
        );

        $selectedNamespace = search(
            label: 'Which namespace would you like to create this widget in?',
            options: function (string $search) use ($keyedNamespaces): array {
                if (blank($search)) {
                    return $keyedNamespaces;
                }

                $search = str($search)->trim()->replace(['\\', '/'], '');

                return array_filter($keyedNamespaces, fn (string $namespace): bool => str($namespace)->replace(['\\', '/'], '')->contains($search, ignoreCase: true));
            },
        );
        if (! is_string($selectedNamespace) || $selectedNamespace === '') {
            throw new FailureCommandOutput;
        }

        $this->widgetsNamespace = $selectedNamespace;

        $directoryIndex = array_search($this->widgetsNamespace, $namespaces, true);
        if ($directoryIndex === false || ! isset($directories[$directoryIndex])) {
            throw new FailureCommandOutput;
        }

        $this->widgetsDirectory = $directories[$directoryIndex];
    }

    protected function configureLocation(): void
    {
        $this->fqn = $this->widgetsNamespace.'\\'.$this->fqnEnd; // @phpstan-ignore assign.propertyType (generated class may not exist yet)
    }

    protected function createTabWidget(): void
    {
        $path = (string) str("{$this->widgetsDirectory}\\{$this->fqnEnd}.php")
            ->replace('\\', '/')
            ->replace('//', '/');

        $this->copyStubToApp('TabsWidget', $path, [
            'class' => $this->fqnEnd,
            'namespace' => $this->widgetsNamespace,
        ]);
    }
}
