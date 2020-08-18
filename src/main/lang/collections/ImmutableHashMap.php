<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Class ImmutableHashMap
 *
 * Immutable unordered hash map implementation
 *
 * @template TKey of string|int|null|bool|object
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

        assert($key === null || is_bool($key));

        foreach ($map->list as $index => $value) {
            if ($value->key === $key) {
                array_splice($map->list, $index, 1);
                break;
            }
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

            return;
        }

        assert($key === null || is_bool($key));

        foreach ($this->list as $index => $item) {
            if ($item->key === $key) {
                array_splice($this->list, $index, 1);
                $this->list[] = $mapPair;

                return;
            }
        }

        $this->list[] = $mapPair;
    }
}
