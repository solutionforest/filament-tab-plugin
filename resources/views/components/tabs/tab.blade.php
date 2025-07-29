@use('SolutionForest\TabLayoutPlugin\Components\Tabs\Tab')
@php
    $livewireId = $this->getId();
    $currentTabId = $getId();
    $generatedLivewireKey = "{$livewireId}." . Tab::class . ".tabs.{$currentTabId}";
@endphp
<div
    x-bind:class="{ 
        'fi-active': tab == @js($currentTabId),
    }"
    x-on:expand="tab = @js($currentTabId)"
    {{ $attributes
        ->merge($getExtraAttributes())
        ->merge([
            'aria-labelledby' => $currentTabId,
            'id' => $currentTabId,
            'role' => 'tabpanel',
            'tabindex' => '0',
            'wire:key' => $generatedLivewireKey,
        ], escape: false)
        ->class(['filament-tabs-component-tab fi-sc-tabs-tab']) 
    }}
    x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = @js($currentTabId)

        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' }), 200)
    "
>
    {{ $getChildComponentContainer() }}
</div>
