<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use function array_values;

/**
 * @template T
 * @psalm-param T ...$elements
 * @return Vector<T>
 */
function vectorOf(...$elements): Vector
{
    /** @var list<T> $elements */
    $elements = $elements['elements'] ?? $elements;
    return new ArrayVector($elements);
}

/**
 * @template T
 * @psalm-param array<array-key, T> $elements
 *
 * @return Vector<T>
 */
function vectorFromArray(array $elements): Vector
{
    return new ArrayVector(array_values($elements));
}

/**
 * @template T
 * @psalm-param T ...$elements
 * @return MutableVector<T>
 */
function mutableVectorOf(...$elements): MutableVector
{
    $elements = $elements['elements'] ?? $elements;

    /** @var list<T> $elements */
    return new MutableArrayVector($elements);
}

/**
 * @template T
 * @psalm-param array<array-key, T> $elements
 * @return MutableVector<T>
 */
function mutableVectorFromArray(array $elements): MutableVector
{
    return new MutableArrayVector(array_values($elements));
}

/**
 * @template T
 * @psalm-param T ...$elements
 * @return ImmutableVector<T>
 */
function immutableVectorOf(...$elements): ImmutableVector
{
    $elements = $elements['elements'] ?? $elements;

    /** @var list<T> $elements */
    return new ImmutableArrayVector($elements);
}

/**
 * @template T
 * @psalm-param array<array-key, T> $elements
 * @return ImmutableVector<T>
 */
function immutableVectorFromArray(array $elements): ImmutableVector
{
    return new ImmutableArrayVector(array_values($elements));
}

/**
 * @template TKey of string|int|object
 * @template TValue
 *
 * @psalm-param TKey $key
 * @psalm-param TValue $value
 *
 * @return MapPair<TKey, TValue>
 */
function pair($key, $value): MapPair
{
    return new MapPair($key, $value);
}

/**
 * @template TKey of string|int|object
 * @template TValue
 *
 * @param \vinyl\std\lang\collections\MapPair<TKey, TValue> ...$pairs
 *
 * @return \vinyl\std\lang\collections\Map<TKey, TValue>
 */
function mapOf(...$pairs): Map
{
    $pairs = $pairs['pairs'] ?? $pairs;

    /** @var list<\vinyl\std\lang\collections\MapPair<TKey, TValue>> $pairs */
    return new HashMap($pairs);
}

/**
 * @template TKey of string|int|object
 * @template TValue
 *
 * @param \vinyl\std\lang\collections\MapPair<TKey, TValue> ...$pairs
 *
 * @return \vinyl\std\lang\collections\MutableMap<TKey, TValue>
 */
function mutableMapOf(...$pairs): MutableMap
{
    $pairs = $pairs['pairs'] ?? $pairs;

    /** @var list<\vinyl\std\lang\collections\MapPair<TKey, TValue>> $pairs */
    return new MutableHashMap($pairs);
}

/**
 * @template TKey of string|int|object
 * @template TValue
 *
 * @param \vinyl\std\lang\collections\MapPair<TKey, TValue> ...$pairs
 *
 * @return \vinyl\std\lang\collections\ImmutableMap<TKey, TValue>
 */
function immutableMapOf(...$pairs): ImmutableMap
{
    $pairs = $pairs['pairs'] ?? $pairs;

    /** @var list<\vinyl\std\lang\collections\MapPair<TKey, TValue>> $pairs */
    return new ImmutableHashMap($pairs);
}

/**
 * @template T of string|object|bool|int|null
 * @psalm-param array<array-key, T> $elements
 *
 * @return Set<T>
 */
function setFromArray(array $elements): Set
{
    return new HashSet($elements);
}

/**
 * @template T of string|object|bool|int|null
 * @psalm-param array<array-key, T> $elements
 *
 * @return Set<T>
 */
function mutableSetFromArray(array $elements = []): Set
{
    return new MutableHashSet($elements);
}
