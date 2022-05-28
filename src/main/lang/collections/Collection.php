<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Interface Collection
 *
 * @template TKey of array-key
 * @template TValue
 * @extends IteratorAggregate<TKey, TValue>
 */
interface Collection extends IteratorAggregate, Countable
{
    /**
     * Returns true if the collection is empty (contains no elements), false otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Checks if the specified element is contained in this collection.
     *
     * @psalm-param TValue $element
     */
    public function contains($element): bool;

    /**
     * Checks if all items in the specified iterable are contained in this collection.
     *
     * @psalm-param iterable<TValue> $items
     */
    public function containsAll(iterable $items): bool;

    /**
     * @return \Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable;
}
