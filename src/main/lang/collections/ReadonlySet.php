<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use ArrayIterator;
use function array_key_exists;
use function array_values;
use function assert;
use function count;
use function is_bool;
use function is_int;
use function is_object;
use function is_string;
use function spl_object_id;

/**
 * Class ReadonlySet
 *
 * @template T of string|object|bool|int|null
 * @implements Set<T>
 */
abstract class ReadonlySet implements Set
{
    protected const CONTAIN_FALSE = 1;
    protected const CONTAIN_TRUE  = 2;
    protected const CONTAIN_NULL  = 4;

    /** @var array<string|int, mixed> */
    protected array $elements = [];

    private int $flags = 0;

    /**
     * ReadonlySet constructor.
     *
     * @param iterable<T> $elements
     *
     * @internal
     */
    public function __construct(iterable $elements)
    {
        foreach ($elements as $element) {
            self::assign($this, $element);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function contains($element): bool
    {
        if (is_object($element)) {
            /** @var object $element */
            $key = self::resolveKey($element);

            return array_key_exists($key, $this->elements);
        }

        if ($element === null) {
            return $this->hasFlag(self::CONTAIN_NULL);
        }

        if ($element === true) {
            return $this->hasFlag(self::CONTAIN_TRUE);
        }

        if ($element === false) {
            return $this->hasFlag(self::CONTAIN_FALSE);
        }

        /** @var string|int $element */
        return array_key_exists($element, $this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function containsAll(iterable $items): bool
    {
        $result = false;
        foreach ($items as $element) {
            if (!$this->contains($element)) {
                return false;
            }
            $result = true;
        }

        return $result;
    }

    /**
     * @return ArrayIterator<int, T>
     */
    public function getIterator(): \Traversable
    {
        $arrayValues = array_values($this->elements);

        if ($this->hasFlag(self::CONTAIN_NULL)) {
            $arrayValues[] = null;
        }

        if ($this->hasFlag(self::CONTAIN_TRUE)) {
            $arrayValues[] = true;
        }

        if ($this->hasFlag(self::CONTAIN_FALSE)) {
            $arrayValues[] = false;
        }

        /** @var array<int, T> $arrayValues */
        return new ArrayIterator($arrayValues);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        $count = count($this->elements);

        if ($this->hasFlag(self::CONTAIN_NULL)) {
            ++$count;
        }

        if ($this->hasFlag(self::CONTAIN_TRUE)) {
            ++$count;
        }

        if ($this->hasFlag(self::CONTAIN_FALSE)) {
            ++$count;
        }

        return $count;
    }

    /**
     * Checks whether given flag is set
     */
    final protected function hasFlag(int $flag): bool
    {
        return ($this->flags & $flag) !== 0;
    }

    /**
     * Set given flag
     */
    final protected function setFlag(int $flag): void
    {
        $this->flags |= $flag;
    }

    /**
     * Unset given flag
     */
    final protected function unsetFlag(int $flag): void
    {
        $this->flags ^= $flag;
    }

    /**
     * @return array<T>
     */
    public function __debugInfo()
    {
        $arrayValues = array_values($this->elements);

        if ($this->hasFlag(self::CONTAIN_NULL)) {
            $arrayValues[] = null;
        }

        if ($this->hasFlag(self::CONTAIN_TRUE)) {
            $arrayValues[] = true;
        }

        if ($this->hasFlag(self::CONTAIN_FALSE)) {
            $arrayValues[] = false;
        }

        /** @var array<int, T> $arrayValues */
        return $arrayValues;
    }

    /**
     * @internal
     * @template T2 of string|object|bool|int|null
     * @psalm-param T2 $element
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    final protected static function assign(ReadonlySet $object, $element): void
    {
        if (is_object($element)) {
            /** @var object $element */
            $key = $element instanceof Identifiable ? $element->identity() : spl_object_id($element);
            $object->elements[$key] = $element;

            return;
        }

        if ($element === null) {
            if (!$object->hasFlag(self::CONTAIN_NULL)) {
                $object->setFlag(self::CONTAIN_NULL);
            }

            return;
        }

        if (is_bool($element)) {
            $flag = $element ? self::CONTAIN_TRUE : self::CONTAIN_FALSE;
            if (!$object->hasFlag($flag)) {
                $object->setFlag($flag);
            }

            return;
        }

        assert(is_string($element) || is_int($element));
        $object->elements[$element] = $element;
    }

    /**
     * @return int|string
     */
    final protected static function resolveKey(object $element)
    {
        return $element instanceof Identifiable ? $element->identity() : spl_object_id($element);
    }
}
