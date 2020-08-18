<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface ImmutableVector
 *
 * @todo use 'with' prefix for methods? withAll, withMany, withReplaced, ...
 *
 * @template T
 * @template-extends Vector<T>
 */
interface ImmutableVector extends Vector
{
    /**
     * Adds the specified element to the end of new list.
     *
     * @psalm-param T $element
     */
    public function with($element): self;

    /**
     * Adds the specified elements to the end of new list.
     *
     * @psalm-param T $elements
     */
    public function withMany(...$elements): self;

    /**
     * Adds elements of specified {@see \vinyl\std\lang\collections\Collection} to the end of new list.
     *
     * @psalm-param Collection<int, T> $collection
     */
    public function withAll(Collection $collection): self;

    /**
     * Replaces the element at the specified position in new list with the specified element.
     *
     * @psalm-param T $element
     *
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function withReplaced(int $index, $element): self;

    /**
     * Removes the element at the specified position in new list. Shifts any subsequent elements to the left (subtracts one from their indices).
     *
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function withRemovedAt(int $index): self;
}
