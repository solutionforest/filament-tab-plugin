<?php

namespace SolutionForest\TabLayoutPlugin\Schemas;

use RuntimeException;

class SimpleTabSchema
{
    public ?string $content = null;

    public ?string $contentType = null;

    /**
     * @var array<string, mixed>
     */
    public array $contentParams = [];

    public ?string $icon = null;

    public ?string $badge = null;

    public function __construct(
        public string $label,
        /** The ID of the tab */
        public ?string $id = null,
    ) {}

    public static function make(string $label, ?string $id = null): self
    {
        return new self($label, $id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function livewireComponent(string $component, array $data = []): static
    {
        $this->content = $component;
        $this->contentType = 'livewire';
        $this->contentParams = $data;

        return $this;
    }

    public function url(string $url, bool $shouldOpenInNewTab = false): static
    {
        $this->content = $url;
        $this->contentType = 'url';
        $this->contentParams = [
            'shouldOpenInNewTab' => $shouldOpenInNewTab,
        ];

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function badge(string $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function parseFormArray(array $data): static
    {
        $fqcn = $data['__fqcn'] ?? static::class;

        unset($data['__fqcn']);

        if (! is_string($fqcn) || ! is_a($fqcn, self::class, true)) {
            $fqcn = static::class;
        }

        // Parsing old keys to new structure START
        if (isset($data['tabKey'])) {
            $data['id'] = $data['tabKey'];
        }
        if (isset($data['tabLabel'])) {
            $data['label'] = $data['tabLabel'];
        }
        // Parsing old keys to new structure END

        $instance = app($fqcn, $data);

        if (! $instance instanceof static) {
            throw new RuntimeException($fqcn.' could not be resolved as '.static::class.'.');
        }

        $content = $data['content'] ?? $data['component'] ?? null;
        $params = $data['contentParams'] ?? $data['params'] ?? [];
        if (is_string($content) && $content !== '') {
            switch ($data['contentType'] ?? 'livewire') {
                case 'url':
                    $instance->url($content);
                    break;
                case 'livewire':
                default:
                    // Livewire component
                    $instance->livewireComponent($content, self::stringKeyedArray(is_array($params) ? $params : []));
                    break;
            }
        }

        if (isset($data['icon']) && is_string($data['icon'])) {
            $instance->icon($data['icon']);
        }

        if (isset($data['badge']) && is_string($data['badge'])) {
            $instance->badge($data['badge']);
        }

        return $instance;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        // Ensure public/protected properties are included in the array representation
        $properties = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (is_string($key)) {
                $properties[$key] = $value;
            }
        }

        $properties['__fqcn'] = static::class;

        return $properties;
    }

    /**
     * @param  array<int|string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function stringKeyedArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function isValidArray(array $data): bool
    {
        try {

            // Check the array is parse from a valid SimpleTabSchema

            $fqcn = $data['__fqcn'] ?? static::class;

            if (! is_string($fqcn) || ! is_a($fqcn, self::class, true)) {
                return false;
            }

            $instance = $fqcn::parseFormArray($data);

            $dataToCheck = $instance->toArray();

            foreach ($dataToCheck as $key => $value) {
                if (in_array($key, ['__fqcn', 'contentType'])) {
                    continue; // Skip
                }
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
