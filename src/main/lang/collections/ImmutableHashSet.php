<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use function assert;
use function is_bool;
use function is_int;
use function is_object;
use function is_string;

/**
 * Class ImmutableHashSet
 *
 * @template TValue of string|object|bool|int|null
 * @extends ReadonlySet<TValue>
 * @implements ImmutableSet<TValue>
 */
final class ImmutableHashSet extends ReadonlySet implements ImmutableSet
{
    /**
     * {@inheritDoc}
     */
    public function with($element): self
    {
        $newSet = clone $this;

        self::assign($newSet, $element);

        return $newSet;
    }

    /**
     * {@inheritDoc}
     */
    public function withMany(...$elements): self
    {
        $newSet = clone $this;

        foreach ($elements as $element) {
            self::assign($newSet, $element);
        }

        return $newSet;
    }

    /**
     * {@inheritDoc}
     */
    public function withAll(iterable $elements): self
    {
        $newSet = clone $this;

        foreach ($elements as $element) {
            self::assign($newSet, $element);
        }

        return $newSet;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     * @psalm-suppress InvalidArgument
     */
    public function withRemoved($element): self
    {
        $newSet = clone $this;

        if (is_object($element)) {
            /** @var object $element */
            $key = self::resolveKey($element);
            unset($newSet->elements[$key]);

            return $newSet;
        }

        if ($element === null) {
            if ($newSet->contains(null)) {
                $newSet->unsetFlag(self::CONTAIN_NULL);
            }

            return $newSet;
        }

        if (is_bool($element)) {
            if ($newSet->contains($element)) {
                $newSet->unsetFlag($element ? self::CONTAIN_TRUE : self::CONTAIN_FALSE);
            }

            return $newSet;
        }

        assert(is_string($element) || is_int($element));
        unset($newSet->elements[$element]);

        return $newSet;
    }
}
