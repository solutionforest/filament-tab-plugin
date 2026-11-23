<?php

namespace SolutionForest\TabLayoutPlugin\Concerns\Components;

use Closure;

trait CanSpanColumns
{
    /**
     * @var array<string, array<string, int|string|null>|int|string|Closure|null>
     */
    protected array $columnSpan = [
        'default' => 1,
        'sm' => null,
        'md' => null,
        'lg' => null,
        'xl' => null,
        '2xl' => null,
    ];

    /**
     * @param  array<string, int|string|Closure|null>|int|string|Closure|null  $span
     */
    public function columnSpan(array|int|string|Closure|null $span): static
    {
        if (! is_array($span)) {
            $span = [
                'default' => $span,
            ];
        }

        $this->columnSpan = array_merge($this->columnSpan, $span);

        return $this;
    }

    public function columnSpanFull(): static
    {
        $this->columnSpan('full');

        return $this;
    }

    /**
     * @return array<string, array<string, int|string|null>|int|string|null>|int|string|null
     */
    public function getColumnSpan(int|string|null $breakpoint = null): array|int|string|null
    {
        $span = $this->columnSpan;

        if ($breakpoint !== null) {
            return $this->evaluateColumnSpanValue($span[$breakpoint] ?? null);
        }

        return array_map(
            fn (mixed $value): array|int|string|null => $this->evaluateColumnSpanValue($value),
            $span,
        );
    }

    /**
     * @return array<string, int|string|null>|int|string|null
     */
    protected function evaluateColumnSpanValue(mixed $value): array|int|string|null
    {
        $evaluated = $this->evaluate($value);

        if (is_int($evaluated) || is_string($evaluated) || $evaluated === null) {
            return $evaluated;
        }

        if (! is_array($evaluated)) {
            return null;
        }

        $result = [];

        foreach ($evaluated as $key => $item) {
            if (! is_string($key)) {
                continue;
            }

            if (is_int($item) || is_string($item) || $item === null) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
