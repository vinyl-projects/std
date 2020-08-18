<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface ImmutableSet
 *
 * @template TValue of string|object|bool|int|null
 * @extends Set<TValue>
 */
interface ImmutableSet extends Set
{
    /**
     * @psalm-param TValue $element
     */
    public function with($element): self;

    /**
     * @psalm-param TValue ...$elements
     */
    public function withMany(...$elements): self;

    /**
     * @psalm-param iterable<TValue> $elements
     */
    public function withAll(iterable $elements): self;

    /**
     * @psalm-param TValue $element
     */
    public function withRemoved($element): self;
}
