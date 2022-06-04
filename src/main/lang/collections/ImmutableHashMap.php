<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use function is_int;

/**
 * Class ImmutableHashMap
 *
 * Immutable unordered hash map implementation
 *
 * @template TKey of string|int|object
 * @template TValue
 * @extends ReadonlyMap<TKey, TValue>
 * @implements ImmutableMap<TKey, TValue>
 */
final class ImmutableHashMap extends ReadonlyMap implements ImmutableMap
{
    /**
     * {@inheritDoc}
     */
    public function with($key, $value): self
    {
        $map = clone $this;
        $map->assign($key, $value);

        return $map;
    }

    /**
     * {@inheritDoc}
     */
    public function withAll(Map $map): self
    {
        $newMap = clone $this;
        foreach ($map as $key => $value) {
            $newMap->assign($key, $value);
        }

        return $newMap;
    }

    /**
     * {@inheritDoc}
     */
    public function withRemoved($key): self
    {
        $map = clone $this;

        if (is_string($key) || is_int($key)) {
            unset($map->pairArrayMap[$key]);

            return $map;
        }

        if (is_object($key)) {
            $resolvedKey = self::resolveKey($key);
            unset($map->pairArrayMap[$resolvedKey]);

            return $map;
        }

        return $map;
    }

    /**
     * @template T1 of string|int|null|bool|object
     * @template T2
     *
     * @psalm-param T1 $key
     * @psalm-param T2 $value
     * @psalm-suppress InvalidPropertyAssignmentValue
     */
    private function assign($key, $value): void
    {
        $mapPair = new MapPair($key, $value);
        if (is_string($key) || is_int($key)) {
            $this->pairArrayMap[$key] = $mapPair;

            return;
        }

        if (is_object($key)) {
            $resolvedKey = self::resolveKey($key);
            $this->pairArrayMap[$resolvedKey] = $mapPair;
        }
    }
}
