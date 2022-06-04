<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\map;

use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\HashMap;
use vinyl\std\lang\collections\Identifiable;
use vinyl\std\lang\collections\ImmutableHashMap;
use function vinyl\std\lang\collections\immutableMapOf;
use function vinyl\std\lang\collections\pair;

/**
 * Class ImmutableHashMapTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ImmutableHashMapTest extends TestCase
{
    /**
     * @test
     */
    public function with(): void
    {
        /** @var \vinyl\std\lang\collections\ImmutableMap<string|int|object, mixed> $immutableMap */
        $immutableMap = immutableMapOf();
        $object = new stdClass();
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identifiable object';
            }
        };
        $newImmutableMap = $immutableMap
            ->with('string', 'string')
            ->with(42, 'int')
            ->with($object, 'object')
            ->with($identifiable, 'identifiable object');

        self::assertTrue($immutableMap->isEmpty());

        self::assertTrue($newImmutableMap->containsKey('string'));
        self::assertTrue($newImmutableMap->containsValue('string'));

        self::assertTrue($newImmutableMap->containsKey(42));
        self::assertTrue($newImmutableMap->containsValue('int'));

        self::assertTrue($newImmutableMap->containsKey($object));
        self::assertTrue($newImmutableMap->containsValue('object'));

        self::assertTrue($newImmutableMap->containsKey($identifiable));
        self::assertTrue($newImmutableMap->containsValue('identifiable object'));
    }

    /**
     * @test
     */
    public function withAll(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<string|int|object, mixed>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
            pair(42, 7),
        ];
        /** @var \vinyl\std\lang\collections\Map<string|int|object, mixed> $map */
        $map = new HashMap($pairs);

        /** @var \vinyl\std\lang\collections\ImmutableMap<string|int|object, mixed> $immutableMap */
        $immutableMap = immutableMapOf();
        $newImmutableMap = $immutableMap->withAll($map);

        foreach ([1, 2, 6, 7] as $item) {
            self::assertTrue($newImmutableMap->containsValue($item));
        }
    }

    /**
     * @test
     */
    public function withRemoved(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<string|int|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
            pair(42, 7),
        ];
        /** @var \vinyl\std\lang\collections\ImmutableMap<string|int|object, mixed> $map */
        $map = new ImmutableHashMap(
            $pairs
        );

        $newMap = $map
            ->withRemoved($stdClass)
            ->withRemoved('b')
            ->withRemoved($identifiable)
            ->withRemoved(42);

        self::assertTrue($newMap->isEmpty());
    }
}
