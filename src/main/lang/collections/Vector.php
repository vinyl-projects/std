<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface Vector
 *
 * Readonly vector interface
 *
 * @template TValue
 * @extends Collection<int, TValue>
 */
interface Vector extends Collection
{
    /**
     * Checks if list has the specified index.
     *
     * @param int $index
     *
     * @return bool
     */
    public function has(int $index): bool;

    /**
     * Returns the element at the specified index in the list.
     *
     * @param int $index
     *
     * @return TValue|null
     */
    public function find(int $index);

    /**
     * Returns the element at the specified index in the list.
     *
     * @return TValue
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function get(int $index);

    /**
     * Returns a {@see Vector} containing the results of applying the given transform function to each element in the original {@see Vector}.
     *
     * @template R
     *
     * @psalm-param callable(TValue):R $transform
     *
     * @return static<R>
     */
    public function map(callable $transform);

    /**
     * Returns array representation of current {@see \vinyl\std\lang\collections\Vector}
     *
     * @psalm-return list<TValue>
     */
    public function toArray(): array;

    /**
     * @todo temp fix for phpstorm autocomplete
     * @return \Traversable<TKey, TValue>
     */
    public function getIterator(): \Traversable;
}
