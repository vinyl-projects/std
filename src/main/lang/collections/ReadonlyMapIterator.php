<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use Iterator;

/**
 * Class ReadonlyMapIterator
 *
 * @template TKey of string|int|object
 * @template-covariant TValue
 * @implements Iterator<TKey, TValue>
 */
final class ReadonlyMapIterator implements Iterator
{
    /**
     * ReadonlyMapIterator constructor.
     *
     * @param array<int|string, MapPair<TKey, TValue>> $pairArrayMap
     *
     * @internal
     */
    public function __construct(private array $pairArrayMap)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function current(): mixed
    {
        $current = current($this->pairArrayMap);
        if ($current !== false) {
            return $current->value;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        if (current($this->pairArrayMap) === false) {
            return;
        }

        next($this->pairArrayMap);
    }

    /**
     * {@inheritDoc}
     */
    public function key(): mixed
    {
        $current = current($this->pairArrayMap);
        if ($current !== false) {
            return $current->key;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return current($this->pairArrayMap) !== false;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->pairArrayMap);
    }
}
