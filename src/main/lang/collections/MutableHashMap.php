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
 * @template TKey of string|int|object
 * @template-covariant TValue
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

        return $this;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     * @psalm-suppress NoValue
     * @psalm-suppress TypeDoesNotContainType
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
     * @psalm-suppress RedundantConditionGivenDocblockType
     * @psalm-suppress NoValue
     * @psalm-suppress TypeDoesNotContainType
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

        return $this;
    }
}
