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
    /** @var array<int|string, MapPair<TKey, TValue>> */
    private $pairArrayMap = [];

    /** @var array<MapPair<TKey, TValue>> */
    private $list = [];

    /**
     * ReadonlyMapIterator constructor.
     *
     * @param array<int|string, MapPair<TKey, TValue>> $pairArrayMap
     * @param array<MapPair<TKey, TValue>>             $list
     *
     * @internal
     */
    public function __construct($pairArrayMap, $list)
    {
        $this->pairArrayMap = $pairArrayMap;
        $this->list = $list;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
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
    public function next()
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
    public function key()
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
    public function valid()
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
    public function rewind()
    {
        reset($this->pairArrayMap);
        reset($this->list);
    }
}
