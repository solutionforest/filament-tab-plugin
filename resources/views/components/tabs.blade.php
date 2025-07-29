@use('SolutionForest\TabLayoutPlugin\Components\Tabs')
@use('SolutionForest\TabLayoutPlugin\Components\Tabs\Tab')
@php
    $livewireId = $this->getId();
    $currentTabId = $getId();
    $generatedLivewireKey = "{$livewireId}." . Tabs::class . ".container";
@endphp
<div
    x-data="{

        tab: null,

        init: function () {
            this.$watch('tab', () => this.updateQueryString())

            this.tab = this.getTabs()[@js($getActiveTab()) - 1]
        },

        getTabs: function () {
            return JSON.parse(this.$refs.tabsData.value)
        },

        updateQueryString: function () {
            if (! @js($isTabPersistedInQueryString())) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(@js($getTabQueryStringKey()), this.tab)

            history.pushState(null, document.title, url.toString())
        },

    }"
    x-cloak
    {{ 
        $attributes
            ->merge($getExtraAttributes())
            ->class([
                'filament-tabs-component rounded-xl shadow-sm border border-gray-300 bg-white',
                'dark:bg-gray-800 dark:border-gray-700',
            ]) 
            ->merge([
                "id=\"{$currentTabId}\"" => filled($currentTabId),
                "wire:key=\"{$generatedLivewireKey}\"",
            ])
    }}
    {{ $getExtraAlpineAttributeBag() }}
>
    <input
        type="hidden"
        value='{{
            collect($getChildComponentContainer()->getComponents())
                ->filter(static fn (Tab $tab): bool => ! $tab->isHidden())
                ->map(static fn (Tab $tab) => $tab->getId())
                ->values()
                ->toJson()
        }}'
        x-ref="tabsData"
    />

    <div
        {!! $getLabel() ? 'aria-label="' . $getLabel() . '"' : null !!}
        role="tablist"
        @class([
            'filament-tabs-component-header rounded-t-xl flex overflow-y-auto bg-gray-100',
            'dark:bg-gray-700',
        ])
    >
        @foreach ($getChildComponentContainer()->getComponents() as $tab)
            @php
                $tabUrl = $tab->getUrl();
            @endphp

            <button
                type="button"
                aria-controls="{{ $tab->getId() }}"
                x-bind:aria-selected="tab === '{{ $tab->getId() }}'"
                @if (filled($tabUrl))
                    onclick="@if ($tab->shouldOpenUrlInNewTab()) window.open('{{ $tabUrl }}', '_blank') @else window.location.href='{{ $tabUrl }}' @endif"
                @else
                    x-on:click="tab = '{{ $tab->getId() }}'"
                @endif
                role="tab"
                x-bind:tabindex="tab === '{{ $tab->getId() }}' ? 0 : -1"
                class="filament-tabs-component-button flex items-center gap-2 shrink-0 p-3 text-sm font-medium"
                x-bind:class="{
                    'text-gray-500 dark:text-gray-400': tab !== '{{ $tab->getId() }}',
                    'filament-tabs-component-button-active bg-white text-primary-600 dark:bg-gray-800': tab === '{{ $tab->getId() }}',
                }"
            >
                @if ($icon = $tab->getIcon())
                    <x-dynamic-component
                        :component="$icon"
                        class="h-5 w-5"
                    />
                @endif

                <span>{{ $tab->getLabel() }}</span>

                @if ($badge = $tab->getBadge())
                    <span
                        class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1.5 min-w-[theme(spacing.5)] py-0.5 tracking-tight"
                        x-bind:class="{
                            'bg-gray-200 dark:bg-gray-600': tab !== '{{ $tab->getId() }}',
                            'fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-primary font-medium': tab === '{{ $tab->getId() }}',
                        }"
                        @style([
                            \Filament\Support\get_color_css_variables(
                                'primary',
                                shades: [50, 400, 600],
                            )
                        ])
                    >
                        {{ $badge }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    @foreach ($getChildComponentContainer()->getComponents() as $tab)
        {{ $tab }}
    @endforeach
</div>
