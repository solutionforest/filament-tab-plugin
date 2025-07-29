@use('SolutionForest\TabLayoutPlugin\Components\Tabs\Tab')
@php
    $livewireId = $this->getId();
    $currentTabId = $getId();
    $generatedLivewireKey = "{$livewireId}." . Tab::class . ".tabs.{$currentTabId}";
@endphp
<div
    aria-labelledby="{{ $currentTabId }}"
    id="{{ $currentTabId }}"
    role="tabpanel"
    tabindex="0"
    x-bind:class="{ 
        'invisible h-0 p-0 overflow-y-hidden': tab !== '{{ $currentTabId }}', 
        'p-6': tab === '{{ $currentTabId }}' 
    }"
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
    {{ $attributes->merge($getExtraAttributes())->class(['filament-tabs-component-tab outline-none']) }}
    wire:key="{{ $generatedLivewireKey }}"
>
    {{ $getChildComponentContainer() }}
</div>
