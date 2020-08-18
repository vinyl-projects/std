<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use OutOfBoundsException;
use function array_key_exists;
use function count;
use function is_int;
use function is_object;
use function is_string;

/**
 * Class ReadonlyMap
 *
 * Unordered implementation of {@see Map}
 *
 * @template TKey of string|int|null|bool|object
 * @template TValue
 * @implements Map<TKey, TValue>
 */
abstract class ReadonlyMap implements Map
{
    /** @var array<int|string, MapPair<TKey, TValue>> */
    protected $pairArrayMap = [];

    /** @var list<MapPair<TKey, TValue>> */
    protected $list = [];

    /**
     * UnorderedMap constructor.
     *
     * @psalm-param iterable<MapPair<TKey, TValue>> $pairs
     * @internal
     */
    public function __construct(iterable $pairs)
    {
        foreach ($pairs as $pair) {
            $key = $pair->key;

            if (is_string($key) || is_int($key)) {
                $this->pairArrayMap[$key] = $pair;
                continue;
            }

            if ($key === null) {
                /** @var MapPair<TKey, TValue> $pair */
                $this->list[] = $pair;
                continue;
            }

            if (is_object($key)) {
                $key = self::resolveKey($key);
                $this->pairArrayMap[$key] = $pair;
                continue;
            }

            if ($key === true) {
                /** @var MapPair<TKey, TValue> $pair */
                $this->list[] = $pair;
                continue;
            }

            if ($key === false) {
                /** @var MapPair<TKey, TValue> $pair */
                $this->list[] = $pair;
                continue;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->pairArrayMap) + count($this->list);
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key): bool
    {
        if (is_string($key) || is_int($key)) {
            return array_key_exists($key, $this->pairArrayMap);
        }

        if (is_object($key)) {
            return array_key_exists(self::resolveKey($key), $this->pairArrayMap);
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $item) {
            if ($item->key === $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function containsValue($element): bool
    {
        /** @var MapPair<TKey, TValue> $pair */
        foreach ($this->pairArrayMap as $pair) {
            if ($pair->value === $element) {
                return true;
            }
        }

        foreach ($this->list as $pair) {
            if ($pair->value === $element) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (is_string($key) || is_int($key)) {
            if (array_key_exists($key, $this->pairArrayMap)) {
                return $this->pairArrayMap[$key]->value;
            }

            throw new OutOfBoundsException("Given key [{$key}] is not present in the map.");
        }

        if (is_object($key)) {
            $resolvedKey = self::resolveKey($key);
            if (array_key_exists($resolvedKey, $this->pairArrayMap)) {
                return $this->pairArrayMap[$resolvedKey]->value;
            }

            $debugKey = get_class($key) . "({$resolvedKey})";
            throw new OutOfBoundsException("Given key [{$debugKey}] is not present in the map.");
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $item) {
            if ($item->key === $key) {
                return $item->value;
            }
        }

        throw new OutOfBoundsException("Given key [{$key}] is not present in the map.");
    }

    /**
     * {@inheritDoc}
     */
    public function find($key)
    {
        if (is_string($key) || is_int($key)) {
            if (array_key_exists($key, $this->pairArrayMap)) {
                return $this->pairArrayMap[$key]->value;
            }

            return null;
        }

        if (is_object($key)) {
            $resolveKey = self::resolveKey($key);
            if (array_key_exists($resolveKey, $this->pairArrayMap)) {
                return $this->pairArrayMap[$resolveKey]->value;
            }

            return null;
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $item) {
            if ($item->key === $key) {
                return $item->value;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function toValueVector(): Vector
    {
        $values = [];

        foreach ($this as $value) {
            $values[] = $value;
        }

        return new ArrayVector($values);
    }

    /**
     * {@inheritDoc}
     */
    public function toKeySet(): Set
    {
        $keys = [];

        foreach ($this as $key => $_) {
            $keys[] = $key;
        }

        return new HashSet($keys);
    }

    public function getIterator()
    {
        return new ReadonlyMapIterator($this->pairArrayMap, $this->list);
    }

    /**
     * @return int|string
     */
    final protected static function resolveKey(object $element)
    {
        return $element instanceof Identifiable ? $element->identity() : spl_object_id($element);
    }

    public function __clone()
    {
        $pairArrayMap = [];
        $list = [];

        foreach ($this->pairArrayMap as $key => $pair) {
            $pairArrayMap[$key] = clone $pair;
        }

        foreach ($this->list as $item) {
            $list[] = clone $item;
        }

        $this->pairArrayMap = $pairArrayMap;
        $this->list = $list;
    }
}
