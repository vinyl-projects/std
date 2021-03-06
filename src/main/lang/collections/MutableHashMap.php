<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use function array_splice;
use function assert;
use function is_bool;
use function is_int;
use function is_object;
use function is_string;

/**
 * Class MutableHashMap
 *
 * Mutable unordered hash map implementation
 *
 * @template TKey of string|int|null|bool|object
 * @template TValue
 * @extends ReadonlyMap<TKey, TValue>
 * @implements MutableMap<TKey, TValue>
 */
final class MutableHashMap extends ReadonlyMap implements MutableMap
{
    /**
     * {@inheritDoc}
     */
    public function clear(): self
    {
        $this->pairArrayMap = [];
        $this->list = [];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function put($key, $value): self
    {
        $mapPair = new MapPair($key, $value);
        if (is_string($key) || is_int($key)) {
            $this->pairArrayMap[$key] = $mapPair;

            return $this;
        }

        if (is_object($key)) {
            $resolvedKey = self::resolveKey($key);
            $this->pairArrayMap[$resolvedKey] = $mapPair;

            return $this;
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $index => $item) {
            if ($item->key === $key) {
                array_splice($this->list, $index, 1);
                $this->list[] = $mapPair;

                return $this;
            }
        }

        $this->list[] = $mapPair;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function putAll(Map $map): self
    {
        foreach ($map as $key => $value) {
            $this->put($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key): self
    {
        if (is_string($key) || is_int($key)) {
            unset($this->pairArrayMap[$key]);

            return $this;
        }

        if (is_object($key)) {
            $resolvedKey = self::resolveKey($key);
            unset($this->pairArrayMap[$resolvedKey]);

            return $this;
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $index => $value) {
            if ($value->key === $key) {
                array_splice($this->list, $index, 1);
                break;
            }
        }

        return $this;
    }
}
