<?php

namespace SolutionForest\TabLayoutPlugin\Widgets;

use Livewire\Component;

class TabWidgetContentConfiguration
{
    /**
     * @param  class-string<Component>  $component
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public readonly string $component,
        public array $params,
        public readonly string $tabLabel,
        public readonly ?string $tabKey = null,
    ) {}

    public static function parseFormArray(array $data): static
    {
        $component = $data['component'] ?? null;
        if (empty($component) || !is_string($component)) {
            throw new \InvalidArgumentException('Each tab must have a "widget" key with a string value.');
        }

        $tabLabel = $data['tabLabel'] ?? $data['label'] ?? uniqid('tab_');
        $tabKey = $data['tabKey'] ?? $data['key'] ?? null;

        return app(static::class, [
            'component' => $component,
            'params' => $data['params'] ?? [],
            'tabLabel' => $tabLabel,
            'tabKey' => $tabKey,
        ]);
    }

    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'params' => $this->params,
            'tabLabel' => $this->tabLabel,
            'tabKey' => $this->tabKey,
        ];
    }

    public static function isValidArray(array $data): bool
    {
        try {
            // Check the array is parse from a valid TabWidgetContentConfiguration
            $static = static::parseFormArray($data);
            foreach ($static->toArray() as $key => $value) {
                if (! array_key_exists($key, $data) || $data[$key] !== $value) {
                    return false;
                }
            }

            return true;

        } catch (\Exception $th) {
            return false;
        }
    }
}
