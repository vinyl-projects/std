<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use Iterator;

/**
 * Class ReadonlyMapIterator
 *
 * @template TKey of string|int|null|bool|object
 * @template TValue
 * @implements Iterator<TKey, TValue>
 */
final class ReadonlyMapIterator implements Iterator
{
    /**
     * ReadonlyMapIterator constructor.
     *
     * @param array<int|string, MapPair<TKey, TValue>> $pairArrayMap
     * @param array<MapPair<TKey, TValue>>             $list
     *
     * @internal
     */
    public function __construct(private array $pairArrayMap, private array $list)
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

        return current($this->list)->value;
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        if (current($this->pairArrayMap) !== false) {
            next($this->pairArrayMap);

            return;
        }

        next($this->list);
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

        return current($this->list)->key;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        $isValid = current($this->pairArrayMap) !== false;

        if ($isValid) {
            return true;
        }

        return current($this->list) !== false;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->pairArrayMap);
        reset($this->list);
    }
}
