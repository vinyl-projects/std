<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

use OutOfBoundsException;
use function array_key_exists;
use function array_merge;
use function array_push;
use function array_splice;
use function assert;

/**
 * Class ImmutableArrayVector
 *
 * @template-covariant T
 * @extends ReadonlyArrayVector<T>
 * @implements ImmutableVector<T>
 */
final class ImmutableArrayVector extends ReadonlyArrayVector implements ImmutableVector
{
    /**
     * {@inheritDoc}
     */
    public function with($element): self
    {
        $newList = clone $this;
        $newList->elements[] = $element;

        return $newList;
    }

    /**
     * {@inheritDoc}
     */
    public function withMany(...$elements): self
    {
        $newList = clone $this;
        $elements = $elements['elements'] ?? $elements;
        /** @var list<T> $elements */
        array_push($newList->elements, ...$elements);

        return $newList;
    }

    /**
     * {@inheritDoc}
     */
    public function withAll(Collection $collection): self
    {
        $newList = clone $this;
        if ($collection instanceof ReadonlyArrayVector) {
            /** @var \vinyl\std\lang\collections\ReadonlyArrayVector<T> $collection */
            $newList->elements = array_merge($newList->elements, $collection->elements);

            return $newList;
        }

        foreach ($collection as $item) {
            $newList->elements[] = $item;
        }

        return $newList;
    }

    /**
     * {@inheritDoc}
     */
    public function withReplaced(int $index, $element): self
    {
        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Given index [{$index}] is out of range.");
        }

        $newList = clone $this;
        assert(array_key_exists($index, $newList->elements));
        $newList->elements[$index] = $element;

        return $newList;
    }

    /**
     * {@inheritDoc}
     */
    public function withRemovedAt(int $index): self
    {
        $newList = clone $this;

        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Given index [{$index}] is out of range.");
        }

        array_splice($newList->elements, $index, 1);

        return $newList;
    }
}
