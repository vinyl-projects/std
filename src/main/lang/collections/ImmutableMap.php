<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface ImmutableMap
 *
 * @template TKey of string|int|object
 * @template TValue
 * @extends Map<TKey, TValue>
 */
interface ImmutableMap extends Map
{
    /**
     * @psalm-param TKey $key
     * @psalm-param TValue $value
     */
    public function with($key, $value): self;

    /**
     * @psalm-param \vinyl\std\lang\collections\Map<TKey, TValue> $map
     */
    public function withAll(Map $map): self;

    /**
     * @psalm-param TKey $key
     */
    public function withRemoved($key): self;
}
