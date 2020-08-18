<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface MutableSet
 *
 * @template T of string|object|bool|int|null
 * @extends Set<T>
 */
interface MutableSet extends Set
{
    /**
     * @psalm-param T $element
     */
    public function add($element): self;

    /**
     * @psalm-param T ...$elements
     */
    public function addMany(...$elements): self;

    /**
     * @psalm-param iterable<T> $elements
     */
    public function addAll(iterable $elements): self;

    /**
     * @psalm-param T $element
     */
    public function remove($element): self;
}
