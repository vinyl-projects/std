<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\set;

use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\ImmutableHashSet;

/**
 * Class ImmutableUnorderedSetTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ImmutableUnorderedSetTest extends TestCase
{
    /**
     * @test
     */
    public function with(): void
    {
        $data = [1, true, false, null];
        /** @var ImmutableHashSet<int|bool|null|string> $set */
        $set = new ImmutableHashSet($data);

        $newSet = $set->with('Hello World');

        self::assertFalse($set->contains('Hello World'));
        self::assertTrue($newSet->contains('Hello World'));
        self::assertNotSame($set, $newSet);
    }

    /**
     * @test
     */
    public function withAll(): void
    {
        $data = [1, true, false, null, 'Hello World'];
        /** @var ImmutableHashSet<int|bool|null|string> $set */
        $set = new ImmutableHashSet([]);

        self::assertTrue($set->isEmpty());
        $newSet = $set->withAll($data);
        self::assertCount(5, $newSet);
        foreach ($newSet as $item) {
            self::assertContains($item, $data);
        }
        self::assertNotSame($set, $newSet);
    }

    /**
     * @test
     */
    public function withMany(): void
    {
        $data = [1, true, false, null, 'Hello World'];
        /** @var ImmutableHashSet<int|bool|null|string> $set */
        $set = new ImmutableHashSet([]);

        $newSet = $set->withMany(...$data);

        self::assertTrue($set->isEmpty());
        self::assertCount(5, $newSet);
        foreach ($newSet as $item) {
            self::assertContains($item, $data);
        }
        self::assertNotSame($set, $newSet);
    }

    /**
     * @test
     */
    public function withRemoved(): void
    {
        $obj = new stdClass();
        $data = [1, true, false, null, 'Hello World', $obj];
        /** @var ImmutableHashSet<int|bool|null|string|object> $set */
        $set = new ImmutableHashSet($data);

        $newSet = $set->withRemoved(1);
        self::assertFalse($newSet->contains(1));
        self::assertNotSame($set, $newSet);

        $newSet = $set->withRemoved(true);
        self::assertFalse($newSet->contains(true));

        $newSet = $set->withRemoved(false);
        self::assertFalse($newSet->contains(false));

        $newSet = $set->withRemoved(null);
        self::assertFalse($newSet->contains(null));

        $newSet = $set->withRemoved('Hello World');
        self::assertFalse($newSet->contains('Hello World'));

        $newSet = $set->withRemoved($obj);
        self::assertFalse($newSet->contains($obj));
    }
}
