<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface Vector
 *
 * Readonly vector interface
 *
 * @template T
 * @extends Collection<int, T>
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
     * @param int $index
     *
     * @return T|null
     */
    public function find(int $index);

    /**
     * Returns the element at the specified index in the list.
     *
     * @return T
     * @throws \OutOfBoundsException if the index is out of range (index < 0 || index >= size())
     */
    public function get(int $index);

    /**
     * Returns a {@see Vector} containing the results of applying the given transform function to each element in the original {@see Vector}.
     *
     * @template R
     *
     * @psalm-param callable(T):R $transform
     *
     * @return Vector<R>
     */
    public function map(callable $transform): Vector;

    /**
     * Returns array representation of current {@see \vinyl\std\lang\collections\Vector}
     *
     * @psalm-return list<T>
     */
    public function toArray(): array;
}
