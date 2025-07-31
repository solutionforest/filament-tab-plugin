<?php

namespace SolutionForest\TabLayoutPlugin\Schemas;

class SimpleTabSchema
{
    /**
     * @var ?string
     */
    public $content = null;

    /**
     * @var ?string
     */
    public $contentType = null;

    /**
     * @var array
     */
    public $contentParams = [];

    /**
     * @var ?string
     */
    public $icon = null;

    /**
     * @var ?string
     */
    public $badge = null;

    public function __construct(
        public string $label,
        /** The ID of the tab */
        public ?string $id = null,
    ) {}

    public static function make(string $label, ?string $id = null): static
    {
        return new static($label, $id);
    }

    /**
     * @param  string  $component
     */
    public function livewireComponent($component, array $data = []): static
    {
        $this->content = $component;
        $this->contentType = 'livewire';
        $this->contentParams = $data;

        return $this;
    }

    /**
     * @param  string  $url
     */
    public function url($url, bool $shouldOpenInNewTab = false): static
    {
        $this->content = $url;
        $this->contentType = 'url';
        $this->contentParams = [
            'shouldOpenInNewTab' => $shouldOpenInNewTab,
        ];

        return $this;
    }

    /**
     * @param  string  $icon
     */
    public function icon($icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param  string  $badge
     */
    public function badge($badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return static
     */
    public static function parseFormArray(array $data)
    {
        // $fqcn = static::class;
        $fqcn = $data['__fqcn'] ?? static::class;

        unset($data['__fqcn']);

        // Parsing old keys to new structure START
        if (isset($data['tabKey'])) {
            $data['id'] = $data['tabKey'];
        }
        if (isset($data['tabLabel'])) {
            $data['label'] = $data['tabLabel'];
        }
        // Parsing old keys to new structure END

        $instance = app($fqcn, $data);

        $content = $data['content'] ?? $data['component'] ?? null;
        $params = $data['contentParams'] ?? $data['params'] ?? [];
        if ($content) {
            switch ($data['contentType'] ?? 'livewire') {
                case 'url':
                    $instance->url($content);
                    break;
                case 'livewire':
                default:
                    // Livewire component
                    $instance->livewireComponent($content, $params);
                    break;
            }
        }

        if (isset($data['icon'])) {
            $instance->icon($data['icon']);
        }

        if (isset($data['badge'])) {
            $instance->badge($data['badge']);
        }

        return $instance;
    }

    public function toArray(): array
    {
        // Ensure public/protected properties are included in the array representation
        $properties = get_object_vars($this);

        $properties['__fqcn'] = static::class;

        return $properties;
    }

    public static function isValidArray(array $data): bool
    {
        try {

            // Check the array is parse from a valid SimpleTabSchema

            // $fqcn = static::class;
            $fqcn = $data['__fqcn'] ?? static::class;

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
