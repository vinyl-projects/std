<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface MutableMap
 *
 * @template TKey of string|int|null|bool|object
 * @template TValue
 * @extends Map<TKey, TValue>
 */
interface MutableMap extends Map
{
    /**
     * Removes all elements from this map.
     */
    public function clear(): self;

    /**
     * Associates the specified value with the specified key in the map.
     *
     * @psalm-param TKey $key
     * @psalm-param TValue $value
     */
    public function put($key, $value): self;

    /**
     * Updates this map with key/value pairs from the specified map.
     *
     * @psalm-param \vinyl\std\lang\collections\Map<TKey, TValue> $map
     */
    public function putAll(Map $map): self;

    /**
     * Removes the specified key and its corresponding value from this map.
     *
     * @psalm-param TKey $key
     */
    public function remove($key): self;
}
