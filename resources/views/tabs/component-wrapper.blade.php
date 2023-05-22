@php
    $rawComponent = $this->getRawComponent() ?? null; 
@endphp
@if ($rawComponent)
    {{ $rawComponent }}
@endif