<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\map;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use stdClass;
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
        $map = mapOf(
            pair($stdClass, 1),
            pair('b', 2),
            pair(true, 3),
            pair(false, 4),
            pair(null, 5),
            pair($identifiable, 6),
        );

        $keys = [];
        $values = [];

        foreach ($map as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        self::assertContains($stdClass, $keys);
        self::assertContains('b', $keys);
        self::assertContains(true, $keys);
        self::assertContains(false, $keys);
        self::assertContains(null, $keys);
        self::assertContains($identifiable, $keys);

        self::assertContains(1, $values);
        self::assertContains(2, $values);
        self::assertContains(3, $values);
        self::assertContains(4, $values);
        self::assertContains(5, $values);
        self::assertContains(6, $values);
    }

    /**
     * @test
     */
    public function nestedIterationOfSameMap(): void
    {
        $map = mapOf(
            pair(1, 1),
            pair(2, 2),
            pair(3, 3),
            pair(4, 4),
        );

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
        $map = mapOf(
            pair($stdClass, null),
            pair($identifiable, null),
            pair('a', null),
            pair(true, null),
            pair(false, null),
            pair(null, null),
        );

        self::assertEquals(6, $map->count());
    }

    /**
     * @test
     */
    public function isEmptyTest(): void
    {
        $map = mapOf();
        self::assertTrue($map->isEmpty());

        $map = mapOf(pair(null, null));
        self::assertFalse($map->isEmpty());
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
        /** @var \vinyl\std\lang\collections\Map<string|int|null|bool|object, mixed> $map */
        $map = mapOf(
            pair(42, 42),
            pair('test', 'test'),
            pair($std, 42),
            pair($identifiable, 42),
            pair(false, null),
            pair(null, null),
        );
        self::assertTrue($map->containsKey(42));
        self::assertTrue($map->containsKey('test'));
        self::assertTrue($map->containsKey($std));
        self::assertTrue($map->containsKey($identifiable));
        self::assertTrue($map->containsKey(false));
        self::assertTrue($map->containsKey(null));
        self::assertFalse($map->containsKey(true));
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
        /** @var \vinyl\std\lang\collections\Map<string|int|null|bool|object, int> $map */
        $map = mapOf(
            pair(42, 1),
            pair($identifiable, 2),
            pair(true, 4),
            pair(false, 5),
            pair(null, 6),
        );

        self::assertTrue($map->containsValue(1));
        self::assertTrue($map->containsValue(2));
        self::assertTrue($map->containsValue(4));
        self::assertTrue($map->containsValue(5));
        self::assertTrue($map->containsValue(6));
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
        $map = mapOf(
            pair($stdClass, 1),
            pair('b', 2),
            pair(true, 3),
            pair(false, 4),
            pair(null, 5),
            pair($identifiable, 6),
        );

        self::assertSame(1, $map->get($stdClass));
        self::assertSame(2, $map->get('b'));
        self::assertSame(3, $map->get(true));
        self::assertSame(4, $map->get(false));
        self::assertSame(5, $map->get(null));
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
        /** @var \vinyl\std\lang\collections\Map<string|int|null|bool|object, int> $map */
        $map = mapOf(
            pair($stdClass, 1),
            pair('b', 2),
            pair(false, 4),
            pair(null, 5),
            pair($identifiable, 6),
        );

        self::assertSame(1, $map->find($stdClass));
        self::assertSame(2, $map->find('b'));
        self::assertSame(4, $map->find(false));
        self::assertSame(5, $map->find(null));
        self::assertSame(6, $map->find($identifiable));

        self::assertSame(null, $map->find(true));
        self::assertSame(null, $map->find('key_not_exists'));
        self::assertSame(null, $map->find(new stdClass()));
    }

    /**
     * @test
     * @dataProvider getThrowsExceptionDataProvider
     *
     * @param string|int|null|bool|object $valueToGet
     */
    public function getThrowsException($valueToGet): void
    {
        $this->expectException(OutOfBoundsException::class);
        /** @var \vinyl\std\lang\collections\Map<string|int|null|bool|object, int> $map */
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
            [true],
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
        $map = mapOf(
            pair($stdClass, 1),
            pair('b', 2),
            pair(true, 3),
            pair(false, 4),
            pair(null, 5),
            pair($identifiable, 6),
        );

        $vector = $map->toValueVector();

        self::assertTrue($vector->contains(1));
        self::assertTrue($vector->contains(2));
        self::assertTrue($vector->contains(3));
        self::assertTrue($vector->contains(4));
        self::assertTrue($vector->contains(5));
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
        $map = mapOf(
            pair($stdClass, 1),
            pair('b', 2),
            pair(true, 3),
            pair(false, 4),
            pair(null, 5),
            pair($identifiable, 6),
            pair(42, 7),
        );

        $keySet = $map->toKeySet();

        self::assertTrue($keySet->contains($stdClass));
        self::assertTrue($keySet->contains('b'));
        self::assertTrue($keySet->contains(true));
        self::assertTrue($keySet->contains(false));
        self::assertTrue($keySet->contains(null));
        self::assertTrue($keySet->contains($identifiable));
        self::assertTrue($keySet->contains(42));
    }

    /**
     * @test
     */
    public function cloneCallClonePairs(): void
    {
        /** @var \vinyl\std\lang\collections\MapPair<int, int> $intPair */
        $intPair = pair(1, 1);
        /** @var \vinyl\std\lang\collections\MapPair<string, int> $stringPair */
        $stringPair = pair('b', 2);
        /** @var \vinyl\std\lang\collections\MapPair<true, int> $truePair */
        $truePair = pair(true, 3);
        /** @var \vinyl\std\lang\collections\MapPair<false, int> $falsePair */
        $falsePair = pair(false, 4);
        /** @var \vinyl\std\lang\collections\MapPair<null, int> $nullPair */
        $nullPair = pair(null, 5);

        $map = mapOf(
            $intPair,
            $stringPair,
            $truePair,
            $falsePair,
            $nullPair,
        );

        $newMap = clone $map;

        $intPair->value = 42;
        $stringPair->value = 42;
        $truePair->value = 42;
        $falsePair->value = 42;
        $nullPair->value = 42;

        self::assertSame(1, $newMap->get(1));
        self::assertSame(2, $newMap->get('b'));
        self::assertSame(3, $newMap->get(true));
        self::assertSame(4, $newMap->get(false));
        self::assertSame(5, $newMap->get(null));
    }
}
