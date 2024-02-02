<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface MutableSet
 *
 * @template-covariant T of string|object|bool|int|null
 * @extends Set<T>
 */
interface MutableSet extends Set
{
    /**
     * @psalm-param T $element
     * @psalm-suppress InvalidTemplateParam
     */
    public function add($element): self;

    /**
     * @psalm-param T ...$elements
     * @psalm-suppress InvalidTemplateParam
     */
    public function addMany(...$elements): self;

    /**
     * @psalm-param iterable<T> $elements
     * @psalm-suppress InvalidTemplateParam
     */
    public function addAll(iterable $elements): self;

    /**
     * @psalm-param T $element
     * @psalm-suppress InvalidTemplateParam
     */
    public function remove($element): self;
}
