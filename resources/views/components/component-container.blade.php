<x-filament-support::grid
    :default="$getColumns('default')"
    :sm="$getColumns('sm')"
    :md="$getColumns('md')"
    :lg="$getColumns('lg')"
    :xl="$getColumns('xl')"
    :two-xl="$getColumns('2xl')"
    class="filament-component-container gap-6"
>
    @foreach ($getComponents(withHidden: false) as $filamentComponent)
        @php
            $columns = [];
            if (method_exists($filamentComponent, 'getColumnSpan')) {
                if ($filamentComponent instanceof \Filament\Widgets\Widget || $filamentComponent instanceof \Filament\Tables\Table) {
                    $widgetColumns = $filamentComponent->getColumnSpan();
                    if (!is_array($widgetColumns)) {
                        $columns = array_merge([
                            'default' => $filamentComponent instanceof \SolutionForest\TabLayoutPlugin\Widgets\TabsWidget
                                ? 'full'
                                : null,
                            'lg' => $widgetColumns,
                        ]);
                    } else {
                        $columns = $widgetColumns;
                    }
                } 
                else {
                    $columns = $filamentComponent->getColumnSpan() ?? [];
                }
            }
        @endphp

        <x-filament-support::grid.column
            :default="$columns['default'] ?? null"
            :sm="$columns['sm'] ?? null"
            :md="$columns['md'] ?? null"
            :lg="$columns['lg'] ?? null"
            :xl="$columns['xl'] ?? null"
            twoXl="{{ $columns['2xl'] ?? null }}"
            :class="(method_exists($filamentComponent, 'getMaxWidth') && $maxWidth = $filamentComponent->getMaxWidth()) ? match ($maxWidth) {
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

            @if ($filamentComponent instanceof \Filament\Widgets\Widget || 
                $filamentComponent instanceof \Filament\Tables\Table ||
                // CreateRecord / EditRecord / ListRecord
                $filamentComponent instanceof \Filament\Resources\Pages\Page ||
                $filamentComponent instanceof \Filament\Pages\Page)

                @php
                    $data = [];

                    if ($filamentComponent instanceof \Filament\Resources\Pages\EditRecord) {
                        $data = array_merge([
                            'record' => $filamentComponent->record?->getKey(),
                        ]);
                    }
                @endphp

                @livewire(\Livewire\Livewire::getAlias(get_class($filamentComponent)), $data)

            @else
                {{ $filamentComponent }}

            @endif
        </x-filament-support::grid.column>
    @endforeach
</x-filament-support::grid>
