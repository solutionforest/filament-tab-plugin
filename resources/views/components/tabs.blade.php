@use('Illuminate\View\ComponentAttributeBag')
@use('SolutionForest\TabLayoutPlugin\Components\Tabs')
@use('SolutionForest\TabLayoutPlugin\Components\Tabs\Tab')
@php
    $livewireId = $this->getId();
    $currentTabId = $getId();
    $generatedLivewireKey = "{$livewireId}." . Tabs::class . ".container";

    $isContained = $isContained();
@endphp
<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tabs', 'filament/schemas') }}"
    x-data="tabsSchemaComponent({
        activeTab: @js($getActiveTab()),
        isTabPersistedInQueryString: @js($isTabPersistedInQueryString()),
        livewireId: @js($livewireId),
        tab: null,
        tabQueryStringKey: @js($getTabQueryStringKey()),
    })"
    x-cloak
    {{ 
        $attributes
            ->merge([
                'id' => $currentTabId,
                'wire:key' => $generatedLivewireKey,
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'filament-tabs-component fi-sc-tabs',
                'fi-contained shadow-sm' => $isContained,
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
        :vertical="false"
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
