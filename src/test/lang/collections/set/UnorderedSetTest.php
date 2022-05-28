<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\set;

use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\Identifiable;
use vinyl\std\lang\collections\HashSet;
use function iterator_to_array;

/**
 * Class UnorderedSetTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UnorderedSetTest extends TestCase
{
    /**
     * @test
     */
    public function contains(): void
    {
        $identifiableClass = new class implements Identifiable {

            public function identity(): string
            {
                return 'identifiableClass';
            }
        };
        $data = [null, 1, 'test', new stdClass(), $identifiableClass, true, false];
        /** @var HashSet<object|string|int|null|bool> $set */
        $set = new HashSet($data);

        foreach ($data as $item) {
            self::assertTrue($set->contains($item));
        }
        self::assertFalse($set->contains(42));
    }

    /**
     * @test
     * @psalm-suppress NoInterfaceProperties
     */
    public function containsAll(): void
    {
        $identifiableClass = new class implements Identifiable {

            public function identity(): string
            {
                return 'identifiableClass';
            }
        };
        $data = [null, 1, 'test', new stdClass(), $identifiableClass];

        /** @var HashSet<object|string|int|null> $set */
        $set = new HashSet($data);

        self::assertTrue($set->containsAll($data));
        $data[] = 42;
        self::assertFalse($set->containsAll($data));
        self::assertFalse($set->containsAll([]));
    }

    /**
     * @test
     */
    public function countTest(): void
    {
        self::assertSame(0, (new HashSet([]))->count());
        self::assertSame(1, (new HashSet([null]))->count());
        self::assertSame(2, (new HashSet([null, true]))->count());
        self::assertSame(3, (new HashSet([null, true, false]))->count());
    }

    /**
     * @test
     */
    public function isEmptyTest(): void
    {
        self::assertTrue((new HashSet([]))->isEmpty());
        self::assertFalse((new HashSet([null]))->isEmpty());
    }

    /**
     * @test
     */
    public function getIterator(): void
    {
        $data = [null, 1, new stdClass(), true, false, 'test'];
        $set = new HashSet($data);
        $setData = iterator_to_array($set->getIterator());

        foreach ($data as $item) {
            self::assertContains($item, $setData);
        }
    }

    /**
     * @test
     */
    public function debugInfo(): void
    {
        $data = [null, 1, new stdClass(), true, false, 'test'];
        $set = new HashSet($data);
        foreach ($set->__debugInfo() as $item) {
            self::assertContains($item, $data);
        }
    }
}
