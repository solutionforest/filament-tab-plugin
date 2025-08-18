@use('SolutionForest\TabLayoutPlugin\Components\Tabs')
@use('SolutionForest\TabLayoutPlugin\Components\Tabs\Tab')
@php
    $livewireId = $this->getId();
    $currentTabId = $getId();
    $generatedLivewireKey = "{$livewireId}." . Tabs::class . ".container";

    $isContained = $isContained();
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
                'filament-tabs-component',
                'rounded-xl shadow-sm border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700' => $isContained,
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

    <x-filament::tabs
        :contained="$isContained"
        :label="$getLabel()"
        x-cloak
        role="tablist"
    >
        @foreach ($getChildComponentContainer()->getComponents() as $tab)
            @php
                $tabUrl = $tab->getUrl();
                $tabKey = $tab->getId();
                $tabBadge = $tab->getBadge();
                $tabBadgeColor = 'primary';
                $tabBadgeIconPosition = "right";
                $tabBadgeIcon = null;
                $tabBadgeTooltip = null;
                $tabIconPosition = 'before';
                $tabIcon = $tab->getIcon();
                $onClickEvent = filled($tabUrl)
                    ? ($tab->shouldOpenUrlInNewTab()
                        ? "window.open('{$tabUrl}', '_blank')"
                        : "window.location.href='{$tabUrl}'")
                    : "tab = '{$tabKey}'";
            @endphp
            
            <x-filament::tabs.item
                :alpine-active="'tab === \'' . $tabKey . '\''"
                :badge="$tabBadge"
                :badge-color="$tabBadgeColor"
                :badge-icon="$tabBadgeIcon"
                :badge-icon-position="$tabBadgeIconPosition"
                :badge-tooltip="$tabBadgeTooltip"
                :icon="$tabIcon"
                :icon-position="$tabIconPosition"
                :x-on:click="$onClickEvent"
            >
                {{ $tab->getLabel() }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    @foreach ($getChildComponentContainer()->getComponents() as $tab)
        {{ $tab }}
    @endforeach
</div>
