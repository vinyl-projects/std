<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use ArrayIterator;
use OutOfBoundsException;
use function array_key_exists;
use function count;
use function in_array;

/**
 * Class ReadonlyArrayVector
 *
 * @template T
 * @template-implements Vector<T>
 */
abstract class ReadonlyArrayVector implements Vector
{
    /** @var list<T> */
    protected array $elements;

    /**
     * ArrayList constructor.
     *
     * @param list<T> $elements
     * @internal to create required instance of collection use one of the available functions from this file src\main\collections\functions.php
     */
    public final function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return count($this->elements) === 0;
    }

    /**
     * @return ArrayIterator<int, T>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $index)
    {
        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Given index [{$index}] is out of range.");
        }

        return $this->elements[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element): bool
    {
        if ($element instanceof Identifiable) {
            $identity = $element->identity();
            foreach ($this->elements as $item) {
                if ($item instanceof Identifiable && $item->identity() === $identity) {
                    return true;
                }
            }

            return false;
        }

        return in_array($element, $this->elements, true);
    }

    /**
     * {@inheritDoc}
     */
    public function has(int $index): bool
    {
        return array_key_exists($index, $this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function find(int $index)
    {
        return $this->elements[$index] ?? null;
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
     * {@inheritDoc}
     */
    public function map(callable $transform)
    {
        $transformed = [];

        foreach ($this as $item) {
            $transformed[] = $transform($item);
        }

        return new static($transformed);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->elements;
    }
}
