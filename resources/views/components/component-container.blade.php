@use('Illuminate\View\Component', 'ViewComponent')
@use('SolutionForest\TabLayoutPlugin\Schemas\Components\LivewireContainer')
<x-filament::grid
    :default="$getColumns('default')"
    :sm="$getColumns('sm')"
    :md="$getColumns('md')"
    :lg="$getColumns('lg')"
    :xl="$getColumns('xl')"
    :two-xl="$getColumns('2xl')"
    class="filament-component-container gap-6"
>
    @foreach ($getComponents(withHidden: false) as $tabContainer)
        @php
            $columns = $tabContainer->getColumnSpan() ?? [];
        @endphp

        <x-filament::grid.column
            :default="$columns['default'] ?? null"
            :sm="$columns['sm'] ?? null"
            :md="$columns['md'] ?? null"
            :lg="$columns['lg'] ?? null"
            :xl="$columns['xl'] ?? null"
            twoXl="{{ $columns['2xl'] ?? null }}"
            :class="(method_exists($tabContainer, 'getMaxWidth') && $maxWidth = $tabContainer->getMaxWidth()) ? match ($maxWidth) {
                'xs' => 'max-w-xs',
                'sm' => 'max-w-sm',
                'md' => 'max-w-md',
                'lg' => 'max-w-lg',
                'xl' => 'max-w-xl',
                '2xl' => 'max-w-2xl',
                '3xl' => 'max-w-3xl',
                '4xl' => 'max-w-4xl',
                '5xl' => 'max-w-5xl',
                '6xl' => 'max-w-6xl',
                '7xl' => 'max-w-7xl',
                default => $maxWidth,
            } : null"
        >
            @if ($tabContainer instanceof ViewComponent)
                {{ $tabContainer->render() }}
            @else
                @php
                    $livewireComponent = $tabContainer->getComponent();
                @endphp
                @if ($livewireComponent)
                    @livewire($livewireComponent, $tabContainer->getData() ?? [])
                @endif
             @endif
        </x-filament::grid.column>
    @endforeach
</x-filament::grid>
