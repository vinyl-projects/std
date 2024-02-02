<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface ImmutableSet
 *
 * @template-covariant TValue of string|object|bool|int|null
 * @extends Set<TValue>
 */
interface ImmutableSet extends Set
{
    /**
     * @psalm-param TValue $element
     * @psalm-suppress InvalidTemplateParam
     */
    public function with($element): self;

    /**
     * @psalm-param TValue ...$elements
     * @psalm-suppress InvalidTemplateParam
     */
    public function withMany(...$elements): self;

    /**
     * @psalm-param iterable<TValue> $elements
     * @psalm-suppress InvalidTemplateParam
     */
    public function withAll(iterable $elements): self;

    /**
     * @psalm-param TValue $element
     * @psalm-suppress InvalidTemplateParam
     */
    public function withRemoved($element): self;
}
