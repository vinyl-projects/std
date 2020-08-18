<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use function assert;
use function is_bool;
use function is_int;
use function is_object;
use function is_string;

/**
 * Class MutableHashSet
 *
 * @template T of string|object|bool|int|null
 * @extends ReadonlySet<T>
 * @implements MutableSet<T>
 */
final class MutableHashSet extends ReadonlySet implements MutableSet
{
    /**
     * {@inheritDoc}
     */
    public function add($element): MutableSet
    {
        self::assign($this, $element);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(...$elements): MutableSet
    {
        foreach ($elements as $item) {
            self::assign($this, $item);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addAll(iterable $elements): MutableSet
    {
        foreach ($elements as $item) {
            self::assign($this, $item);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     * @psalm-suppress InvalidArgument
     */
    public function remove($element): MutableSet
    {
        if (is_object($element)) {
            /** @var object $element */
            $key = self::resolveKey($element);
            unset($this->elements[$key]);

            return $this;
        }

        if ($element === null) {
            if ($this->contains(null)) {
                $this->unsetFlag(self::CONTAIN_NULL);
            }

            return $this;
        }

        if (is_bool($element)) {
            if ($this->contains($element)) {
                $this->unsetFlag($element ? self::CONTAIN_TRUE : self::CONTAIN_FALSE);
            }

            return $this;
        }

        assert(is_string($element) || is_int($element));
        unset($this->elements[$element]);

        return $this;
    }
}
