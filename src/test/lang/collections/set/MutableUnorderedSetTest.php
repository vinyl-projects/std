<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\set;

use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\MutableHashSet;

/**
 * Class MutableUnorderedSetTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class MutableUnorderedSetTest extends TestCase
{
    /**
     * @test
     */
    public function add(): void
    {
        /** @var MutableHashSet<string|bool|null|object> $set */
        $set = new MutableHashSet([]);
        self::assertTrue($set->isEmpty());

        $obj = new stdClass();
        $set->add('test')->add(null)->add(true)->add(false)->add($obj);
        self::assertTrue($set->contains('test'));
        self::assertTrue($set->contains(null));
        self::assertTrue($set->contains(false));
        self::assertTrue($set->contains(true));
        self::assertTrue($set->contains($obj));

    }

    /**
     * @test
     */
    public function addMany(): void
    {
        /** @var MutableHashSet<string|bool|null|object> $set */
        $set = new MutableHashSet([]);

        self::assertTrue($set->isEmpty());

        $obj = new stdClass();
        $set->addMany('test', true, false, null, $obj);
        self::assertTrue($set->contains('test'));
        self::assertTrue($set->contains(null));
        self::assertTrue($set->contains(false));
        self::assertTrue($set->contains(true));
        self::assertTrue($set->contains($obj));
    }

    /**
     * @test
     */
    public function addAll(): void
    {
        /** @var MutableHashSet<string|bool|null|object> $set */
        $set = new MutableHashSet([]);

        self::assertTrue($set->isEmpty());

        $obj = new stdClass();
        $data = ['test', true, false, null, $obj];
        $set->addAll($data);
        self::assertTrue($set->contains('test'));
        self::assertTrue($set->contains(null));
        self::assertTrue($set->contains(false));
        self::assertTrue($set->contains(true));
        self::assertTrue($set->contains($obj));
    }

    /**
     * @test
     */
    public function remove(): void
    {
        $obj = new stdClass();
        $data = ['test', true, false, null, $obj];
        /** @var MutableHashSet<string|bool|null|object> $set */
        $set = new MutableHashSet($data);
        $set->remove('test')->remove(null)->remove(false)->remove(true)->remove($obj);

        self::assertFalse($set->contains('test'));
        self::assertFalse($set->contains(null));
        self::assertFalse($set->contains(false));
        self::assertFalse($set->contains(true));
        self::assertFalse($set->contains($obj));
    }
}
