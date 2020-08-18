<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use ArrayIterator;
use vinyl\std\lang\collections\Collection;
use vinyl\std\lang\collections\ImmutableVector;
use function iterator_to_array;
use function vinyl\std\lang\collections\vectorOf;

/**
 * Class ImmutableVectorTest
 */
abstract class ImmutableVectorTest extends ReadonlyVectorTest
{
    /**
     * @test
     */
    public function add(): void
    {
        $list = static::createList();
        $newList = $list->with(1);

        $expectedValues = [1];
        self::assertNotSame($list, $newList);
        self::assertSame([], iterator_to_array($list->getIterator()));
        self::assertSame($expectedValues, iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function addAll(): void
    {
        $list = static::createList(1, 2, 3);
        $readonlyList = vectorOf(4, 5, 6);
        $newList = $list->withAll($readonlyList);

        self::assertNotSame($list, $newList);
        self::assertSame([1, 2, 3], iterator_to_array($list->getIterator()));
        self::assertSame([1, 2, 3, 4, 5, 6], iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function addAllWithCustomCollection(): void
    {
        $customCollection = new class implements Collection {
            public function isEmpty(): bool
            {
                throw new \RuntimeException('Not implemented.');
            }

            public function contains($element): bool
            {
                throw new \RuntimeException('Not implemented.');
            }

            public function containsAll(iterable $items): bool
            {
                throw new \RuntimeException('Not implemented.');
            }

            public function getIterator()
            {
                return new ArrayIterator([1, 2, 3]);
            }

            public function count()
            {
                throw new \RuntimeException('Not implemented.');
            }
        };

        $list = static::createList(1,2,3);
        $newList = $list->withAll($customCollection);

        self::assertNotSame($list, $newList);
        self::assertSame([1,2,3], iterator_to_array($list->getIterator()));
        self::assertSame([1,2,3,1,2,3], iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function addMany(): void
    {
        $list = static::createList();
        $newList = $list->withMany(1, 2, 3, 4);

        self::assertNotSame($list, $newList);
        self::assertSame([], iterator_to_array($list->getIterator()));
        self::assertSame([1, 2, 3, 4], iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function replace(): void
    {
        $list = static::createList(1, 2, 3);
        $newList = $list->withReplaced(2, 42);
        self::assertNotSame($list, $newList);
        self::assertSame([1, 2, 3], iterator_to_array($list->getIterator()));
        self::assertSame([1, 2, 42], iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function replaceThrowsOutOfBoundException(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Given index [100] is out of range.');
        $list = static::createList(1, 2, 3);
        $list->withReplaced(100, 42);
    }

    /**
     * @test
     */
    public function removeAt(): void
    {
        $list = static::createList(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $newList = $list->withRemovedAt(4);
        self::assertNotSame($list, $newList);
        self::assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], iterator_to_array($list->getIterator()));
        self::assertSame([1, 2, 3, 4, 6, 7, 8, 9], iterator_to_array($newList->getIterator()));
    }

    /**
     * @test
     */
    public function removeAtThrowsOutOfBoundException(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Given index [42] is out of range.');
        $list = static::createList(1, 2, 3);
        $list->withRemovedAt(42);
    }

    abstract protected static function createList(...$elements): ImmutableVector;
}