<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use OutOfBoundsException;
use function array_key_exists;
use function array_merge;
use function array_push;
use function array_splice;

/**
 * Class MutableArrayVector
 *
 * @template-covariant T
 * @implements MutableVector<T>
 * @extends ReadonlyArrayVector<T>
 */
final class MutableArrayVector extends ReadonlyArrayVector implements MutableVector
{
    /**
     * {@inheritDoc}
     */
    public function add($element): void
    {
        $this->elements[] = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(...$elements): void
    {
        /** @var list<T> $elements */
        $elements = $elements['elements'] ?? $elements;
        array_push($this->elements, ...$elements);
    }

    /**
     * {@inheritDoc}
     */
    public function addAll(iterable $collection): void
    {
        if ($collection instanceof ReadonlyArrayVector) {
            /** @var \vinyl\std\lang\collections\ReadonlyArrayVector<T> $collection */
            $this->elements = array_merge($this->elements, $collection->elements);

            return;
        }

        foreach ($collection as $item) {
            $this->elements[] = $item;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function replace(int $index, $element): void
    {
        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Given index [{$index}] is out of range.");
        }

        $this->elements[$index] = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function removeAt(int $index): void
    {
        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Given index [{$index}] is out of range.");
        }

        array_splice($this->elements, $index, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->elements = [];
    }
}
