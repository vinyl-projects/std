<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\Identifiable;
use vinyl\std\lang\collections\Vector;

/**
 * Class ReadonlyVectorTest
 */
abstract class ReadonlyVectorTest extends TestCase
{
    /**
     * @test
     */
    public function find(): void
    {
        $list = static::createList(1, 2, 3);
        self::assertSame($list->find(0), 1);
        self::assertSame($list->find(1), 2);
        self::assertSame($list->find(2), 3);
        self::assertNull($list->find(3));
    }

    /**
     * @test
     */
    public function get(): void
    {
        $list = static::createList(1, 2, 3);
        self::assertSame($list->get(0), 1);
        self::assertSame($list->get(1), 2);
        self::assertSame($list->get(2), 3);
    }

    /**
     * @test
     */
    public function getThrowsOutOfBoundException(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Given index [1] is out of range.');
        $list = static::createList();
        $list->get(1);
    }

    /**
     * @test
     */
    public function listIsEmpty(): void
    {
        $emptyList = static::createList();
        $list = static::createList(1);

        self::assertTrue($emptyList->isEmpty());
        self::assertFalse($list->isEmpty());
    }

    /**
     * @test
     */
    public function listCount(): void
    {
        $list = static::createList(1, 2, 3, 4);
        self::assertSame(4, $list->count());
    }

    /**
     * @test
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function listIsIterable(): void
    {
        $list = static::createList(1, 2, 3);
        self::assertIsIterable($list);
    }

    /**
     * @test
     */
    public function has(): void
    {
        $list = static::createList(1, 2, 3);

        self::assertTrue($list->has(2));
        self::assertFalse($list->has(3));
    }

    /**
     * @test
     */
    public function contains(): void
    {
        $arr = [1, 2, 3];
        $obj = new stdClass();

        $idn1 = new class implements Identifiable {
            public function identity(): string
            {
                return '1';
            }
        };

        $idn3 = new class implements Identifiable {
            public function identity(): string
            {
                return 'test';
            }
        };

        $data = ['1', 2.0, true, null, 'hello', $obj, $arr, $idn1,];
        /** @var Vector<mixed> $list */
        $list = static::createList(...$data);

        foreach ($data as $item) {
            self::assertTrue($list->contains($item));
        }

        self::assertTrue($list->contains(clone $idn1));
        self::assertFalse($list->contains($idn3));
    }

    /**
     * @test
     */
    public function containsAll(): void
    {
        /** @var Vector<int> $list */
        $list = static::createList(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $secondList = static::createList(9, 8, 7);

        self::assertTrue($list->containsAll($secondList));
        self::assertFalse($list->containsAll(static::createList(10)));
        self::assertFalse($list->containsAll(static::createList()));
    }

    /**
     * @test
     */
    public function map(): void
    {
        $list = static::createList(1, 2, 3);
        $transformedList = $list->map(fn(int $value): int => $value * $value);
        self::assertSame([1, 4, 9], $transformedList->toArray());
        self::assertSame(get_class($list), get_class($transformedList));
    }

    /**
     * @template T
     * @psalm-param T ...$elements
     * @return \vinyl\std\lang\collections\Vector<T>
     */
    abstract protected static function createList(...$elements): Vector;
}
