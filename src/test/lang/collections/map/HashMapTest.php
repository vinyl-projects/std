<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\map;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\collections\HashMap;
use vinyl\std\lang\collections\Identifiable;
use function vinyl\std\lang\collections\mapOf;
use function vinyl\std\lang\collections\pair;

/**
 * Class HashMapTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class HashMapTest extends TestCase
{
    /**
     * @test
     */
    public function iteration(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
        ];
        $map = new HashMap($pairs);

        $keys = [];
        $values = [];

        foreach ($map as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        self::assertContains($stdClass, $keys);
        self::assertContains('b', $keys);
        self::assertContains($identifiable, $keys);

        self::assertContains(1, $values);
        self::assertContains(2, $values);
        self::assertContains(6, $values);
    }

    /**
     * @test
     */
    public function nestedIterationOfSameMap(): void
    {
        $pairs = [
            pair(1, 1),
            pair(2, 2),
            pair(3, 3),
            pair(4, 4)
        ];
        $map = new HashMap($pairs);

        $keyValues = [];
        foreach ($map as $key => $value) {
            $keyValues[] = $key;
            $keyValues[] = $value;

            foreach ($map as $key2 => $value2) {
                $keyValues[] = $key2;
                $keyValues[] = $value2;
            }
        }

        // @formatter:off
        self::assertSame([
                1,1, 1,1,2,2,3,3,4,4,
                2,2, 1,1,2,2,3,3,4,4,
                3,3, 1,1,2,2,3,3,4,4,
                4,4, 1,1,2,2,3,3,4,4,
            ],
            $keyValues
        );
        // @formatter:on
    }

    /**
     * @test
     */
    public function countTest(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, null),
            pair($identifiable, null),
            pair('a', null),
        ];
        $map = new HashMap($pairs);

        self::assertEquals(3, $map->count());
    }

    /**
     * @test
     */
    public function isEmptyTest(): void
    {
        $map = mapOf();
        self::assertTrue($map->isEmpty());
    }

    /**
     * @test
     */
    public function containKey(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $std = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair(42, 42),
            pair('test', 'test'),
            pair($std, 42),
            pair($identifiable, 42)
        ];
        $map = new HashMap($pairs);
        self::assertTrue($map->containsKey(42));
        self::assertTrue($map->containsKey('test'));
        self::assertTrue($map->containsKey($std));
        self::assertTrue($map->containsKey($identifiable));
    }

    /**
     * @test
     */
    public function containsValue(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair(42, 1),
            pair($identifiable, 2),
            pair('string', 7),
        ];

        $map = new HashMap($pairs);

        self::assertTrue($map->containsValue(1));
        self::assertTrue($map->containsValue(2));
        self::assertTrue($map->containsValue(7));
        self::assertFalse($map->containsValue(42));
    }

    /**
     * @test
     */
    public function get(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
        ];
        $map = new HashMap($pairs);

        self::assertSame(1, $map->get($stdClass));
        self::assertSame(2, $map->get('b'));
        self::assertSame(6, $map->get($identifiable));
    }

    /**
     * @test
     */
    public function find(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
        ];

        $map = new HashMap($pairs);

        self::assertSame(1, $map->find($stdClass));
        self::assertSame(2, $map->find('b'));
        self::assertSame(6, $map->find($identifiable));

        self::assertNull($map->find('key_not_exists'));
        self::assertNull($map->find(new stdClass()));
    }

    /**
     * @test
     * @dataProvider getThrowsExceptionDataProvider
     */
    public function getThrowsException(object|int|string $valueToGet): void
    {
        $this->expectException(OutOfBoundsException::class);
        /** @var \vinyl\std\lang\collections\Map<string|int|object, int> $map */
        $map = mapOf();
        $map->get($valueToGet);
    }

    public function getThrowsExceptionDataProvider(): array
    {
        return [
            [new stdClass()],
            [new class implements Identifiable {
                public function identity(): string
                {
                    return '42';
                }
            }],
            ['string key'],
            ['int key']
        ];
    }

    /**
     * @test
     */
    public function toValueVector(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
        ];
        $map = new HashMap($pairs);

        $vector = $map->toValueVector();

        self::assertTrue($vector->contains(1));
        self::assertTrue($vector->contains(2));
        self::assertTrue($vector->contains(6));
    }

    /**
     * @test
     */
    public function toKeySet(): void
    {
        $identifiable = new class implements Identifiable {
            public function identity(): string
            {
                return 'identity';
            }
        };
        $stdClass = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair($stdClass, 1),
            pair('b', 2),
            pair($identifiable, 6),
            pair(42, 7),
        ];
        $map = new HashMap($pairs);

        $keySet = $map->toKeySet();

        self::assertTrue($keySet->contains($stdClass));
        self::assertTrue($keySet->contains('b'));
        self::assertTrue($keySet->contains($identifiable));
        self::assertTrue($keySet->contains(42));
    }

    /**
     * @test
     */
    public function cloneCallClonePairs(): void
    {
        $key = new stdClass();
        $value = new stdClass();
        /** @var list<\vinyl\std\lang\collections\MapPair<int|string|object, int>> $pairs */
        $pairs = [
            pair(1, 1),
            pair('b', 2),
            pair($key, $value)
        ];

        /** @var \vinyl\std\lang\collections\Map<int|string|object, int> $map */
        $map = new HashMap($pairs);

        $newMap = clone $map;

        foreach ($pairs as $pair) {
            $pair->value = 42;
        }

        self::assertSame(1, $newMap->get(1));
        self::assertSame(2, $newMap->get('b'));
        self::assertSame($value, $newMap->get($key));
    }
}
