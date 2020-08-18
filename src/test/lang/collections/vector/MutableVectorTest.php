<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use ArrayIterator;
use vinyl\std\lang\collections\Collection;
use vinyl\std\lang\collections\MutableVector;
use function iterator_to_array;
use function vinyl\std\lang\collections\immutableVectorOf;
use function vinyl\std\lang\collections\vectorOf;

/**
 * Class MutableVectorTest
 */
abstract class MutableVectorTest extends ReadonlyVectorTest
{
    /**
     * @test
     */
    public function add(): void
    {
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList();
        $mutableList->add(1);
        $mutableList->add(2);
        $mutableList->add(3);

        $expectedValues = [1, 2, 3];

        self::assertSame($expectedValues, iterator_to_array($mutableList->getIterator()));
    }

    /**
     * @test
     */
    public function addAll(): void
    {
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList(1, 2, 3);
        /** @var \vinyl\std\lang\collections\ImmutableVector<int> $list */
        $list = immutableVectorOf(4, 5, 6);
        $mutableList->addAll($list);

        self::assertSame([1, 2, 3, 4, 5, 6], iterator_to_array($mutableList->getIterator()));
    }

    /**
     * @test
     */
    public function addAllWithIterable(): void
    {
        /** @var MutableVector<int> $list */
        $list = static::createList(1, 2, 3);
        $list->addAll([1, 2, 3, 4]);

        self::assertSame([1, 2, 3, 1, 2, 3, 4], iterator_to_array($list->getIterator()));
    }

    /**
     * @test
     */
    public function addMany(): void
    {
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList();
        $mutableList->addMany(1, 2, 3, 4);

        self::assertSame([1, 2, 3, 4], iterator_to_array($mutableList->getIterator()));
    }

    /**
     * @test
     */
    public function clear(): void
    {
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList(1, 2, 3);
        $mutableList->clear();
        self::assertSame(0, $mutableList->count());
    }

    /**
     * @test
     */
    public function replace(): void
    {
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList(1, 2, 3);
        $mutableList->replace(2, 42);
        self::assertSame([1, 2, 42], iterator_to_array($mutableList->getIterator()));
    }

    /**
     * @test
     */
    public function replaceThrowsOutOfBoundException(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Given index [100] is out of range.');
        /** @var MutableVector<int> $mutableList */
        $mutableList = static::createList(1, 2, 3);
        $mutableList->replace(100, 42);
    }

    /**
     * @test
     */
    public function removeAt(): void
    {
        $mutableList = static::createList(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $mutableList->removeAt(4);
        self::assertSame([1, 2, 3, 4, 6, 7, 8, 9], iterator_to_array($mutableList->getIterator()));
    }

    /**
     * @test
     */
    public function removeAtThrowsOutOfBoundException(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Given index [42] is out of range.');
        $mutableList = static::createList(1, 2, 3);
        $mutableList->removeAt(42);
    }

    /**
     * @template T
     * @psalm-param T ...$elements
     * @return \vinyl\std\lang\collections\MutableVector<T>
     */
    abstract protected static function createList(...$elements): MutableVector;
}
