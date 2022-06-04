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
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|object, mixed> $map */
        $map = mutableMapOf();

        $objectKey = new \stdClass();
        $map->put(1, 1)
            ->put('01', '01')
            ->put('1', '1')
            ->put('hello', 'world')
            ->put($objectKey, 'object key');

        self::assertSame('01', $map->get('01'));
        self::assertSame('1', $map->get('1'));
        self::assertSame('world', $map->get('hello'));
    }

    /**
     * @test
     */
    public function putAll(): void
    {
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|object, mixed> $map */
        $map = mutableMapOf();

        /** @var \vinyl\std\lang\collections\MutableMap<string|int|object, mixed> $map2 */
        $map2 = mutableMapOf();
        $objectKey = new \stdClass();
        $map2->put(1, 1)
            ->put('hello', 'world')
            ->put($objectKey, 'object key');
        $map->putAll($map2);

        self::assertSame(1, $map->get(1));
        self::assertSame('world', $map->get('hello'));
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
        /** @var list<\vinyl\std\lang\collections\MapPair<string|int|object, mixed>> $pairs */
        $pairs = [
            pair($objectKey, 1),
            pair('b', 2),
            pair($identifiable, 6),
            pair(1, '1'),
            pair('1', '1s'),
        ];
        /** @var \vinyl\std\lang\collections\MutableMap<string|int|object, mixed> $map */
        $map = new MutableHashMap($pairs);

        $map->remove($objectKey)
            ->remove('b')
            ->remove($identifiable)
            ->remove(1);

        self::assertNull($map->find($objectKey));
        self::assertNull($map->find('b'));
        self::assertNull($map->find($identifiable));
        self::assertNull($map->find(1));
        self::assertNull($map->find('1'));


        self::assertCount(0, $map);
    }
}
