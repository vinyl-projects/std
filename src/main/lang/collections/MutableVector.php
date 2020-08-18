<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface MutableVector
 *
 * @template T
 * @extends Vector<T>
 */
interface MutableVector extends Vector
{
    /**
     * Adds the specified element to the end of this list.
     *
     * @psalm-param T $element
     */
    public function add($element): void;

    /**
     * Adds the specified elements to the end of this list.
     *
     * @psalm-param T ...$elements
     */
    public function addMany(...$elements): void;

    /**
     * Adds elements of specified iterable to the end of this list.
     *
     * @psalm-param iterable<T> $collection
     */
    public function addAll(iterable $collection): void;

    /**
     * Replaces the element at the specified position in this list with the specified element.
     *
     * @psalm-param T $element
     *
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function replace(int $index, $element): void;

    /**
     * Removes the element at the specified position in this list. Shifts any subsequent elements to the left (subtracts one from their indices).
     *
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function removeAt(int $index): void;

    /**
     * Removes all elements from this collection.
     */
    public function clear(): void;
}
