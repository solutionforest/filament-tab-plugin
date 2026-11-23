<?php

namespace Filament;

/**
 * Analysis fixture so PHPStan can see Filament\Panel.
 *
 * This package depends on filament/widgets and filament/support, which type
 * command helpers against Panel, but does not require filament/filament
 * where the real class lives.
 */
class Panel
{
    /**
     * @return array<int, string>
     */
    public function getWidgetDirectories(): array
    {
        return [];
    }

    /**
     * @return array<int, string>
     */
    public function getWidgetNamespaces(): array
    {
        return [];
    }
}
