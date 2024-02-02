<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use Countable;
use IteratorAggregate;

/**
 * Interface Map
 *
 * @template TKey of string|int|object
 * @template-covariant TValue
 * @extends IteratorAggregate<TKey, TValue>
 */
interface Map extends IteratorAggregate, Countable
{
    /**
     * Returns true if the {@see Map} is empty, false otherwise
     */
    public function isEmpty(): bool;

    /**
     * @psalm-param TKey $key
     *
     * @return bool
     */
    public function containsKey($key): bool;

    /**
     * @psalm-param TValue $element
     * @psalm-suppress InvalidTemplateParam
     *
     * @return bool
     */
    public function containsValue($element): bool;

    /**
     * Returns the value corresponding to the given key
     *
     * @psalm-param TKey $key
     *
     * @throws \OutOfBoundsException
     * @psalm-return TValue
     */
    public function get($key);

    /**
     * Returns the value corresponding to the given key or null if such a key is not present in the map
     *
     * @psalm-param TKey $key
     *
     * @psalm-return TValue|null
     */
    public function find($key);

    /**
     * Returns a {@see \vinyl\std\lang\collections\Vector} containing all values
     *
     * @return \vinyl\std\lang\collections\Vector<TValue>
     */
    public function toValueVector(): Vector;

    /**
     * Returns a {@see \vinyl\std\lang\collections\Set} containing all keys
     *
     * @return \vinyl\std\lang\collections\Set<TKey>
     */
    public function toKeySet(): Set;
}
