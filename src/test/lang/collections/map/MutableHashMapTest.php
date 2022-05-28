<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\map;

use PHPUnit\Framework\TestCase;
use vinyl\std\lang\collections\Identifiable;
use vinyl\std\lang\collections\MutableHashMap;
use function vinyl\std\lang\collections\mutableMapOf;
use function vinyl\std\lang\collections\pair;

/**
 * Class MutableHashMapTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MutableHashMapTest extends TestCase
{
    /**
     * @test
     */
    public function clear(): void
    {
        $map = mutableMapOf(pair('test', 'test'));
        $map->clear();

        self::assertTrue($map->isEmpty());
    }

    /**
     * @test
     */
    public function putValueToMap(): void
    {
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|null|bool|object, mixed> $map */
        $map = mutableMapOf();

        $objectKey = new \stdClass();
        $map->put(1, 1)
            ->put('hello', 'world')
            ->put($objectKey, 'object key')
            ->put(null, 'null')
            ->put(true, 'true')
            ->put(false, 'false');

        self::assertSame(1, $map->get(1));
        self::assertSame('world', $map->get('hello'));
        self::assertSame('null', $map->get(null));
        self::assertSame('true', $map->get(true));
        self::assertSame('false', $map->get(false));
    }

    /**
     * @test
     */
    public function putAndRemoveSpecialValues(): void
    {
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|null|bool|object, mixed> $map */
        $map = mutableMapOf();
        $map->put(null, 1)->put(null, 2);
        $map->put(false, 1)->put(false, 2);
        $map->put(true, 1)->put(true, 2);
        $map->remove(null)->remove(true)->remove(false);

        self::assertCount(0, $map);
    }

    /**
     * @test
     */
    public function putAll(): void
    {
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|null|bool|object, mixed> $map */
        $map = mutableMapOf();

        /** @var \vinyl\std\lang\collections\MutableMap<string|int|null|bool|object, mixed> $map2 */
        $map2 = mutableMapOf();
        $objectKey = new \stdClass();
        $map2->put(1, 1)
            ->put('hello', 'world')
            ->put($objectKey, 'object key')
            ->put(null, 'null')
            ->put(true, 'true')
            ->put(false, 'false');
        $map->putAll($map2);

        self::assertSame(1, $map->get(1));
        self::assertSame('world', $map->get('hello'));
        self::assertSame('null', $map->get(null));
        self::assertSame('true', $map->get(true));
        self::assertSame('false', $map->get(false));
        self::assertSame('object key', $map->get($objectKey));
    }

    /**
     * @test
     */
    public function remove(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $objectKey = new \stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<string|int|null|bool|object, mixed>> $pairs */
        $pairs = [
            pair($objectKey, 1),
            pair('b', 2),
            pair(true, 3),
            pair(false, 4),
            pair($identifiable, 6),
            pair(null, 5),
            pair(1, '1'),
            pair('1', '1s'),
        ];
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|null|bool|object, mixed> $map */
        $map = new MutableHashMap($pairs);

        $map->remove($objectKey)
            ->remove('b')
            ->remove(true)
            ->remove(false)
            ->remove($identifiable)
            ->remove(null)
            ->remove(1);

        self::assertNull($map->find($objectKey));
        self::assertNull($map->find('b'));
        self::assertNull($map->find(true));
        self::assertNull($map->find(false));
        self::assertNull($map->find($identifiable));
        self::assertNull($map->find(1));
        self::assertNull($map->find('1'));

        $getNull = false;
        try {
            $map->get(null);
        } catch (\OutOfBoundsException $e) {
            $getNull = true;
        }
        self::assertTrue($getNull);

        self::assertCount(0, $map);
    }
}
