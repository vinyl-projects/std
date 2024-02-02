<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use vinyl\std\lang\ByteUnit;

/**
 * Class ByteUnit2Test
 *
 * The test class ByteUnit2Test is written to test method `create` on ByteUnit class.
 * Individual test methods are written to focus on each potential use case of `create` method.
 *
 */
final class ByteUnitTest extends TestCase
{
    /**
     * Tests if the method `create` assigns correct bytes value to the ByteUnit object.
     */
    #[Test]
    public function createAssignsCorrectBytesValue(): void
    {
        $unit = ByteUnit::create(256);
        $this->assertEquals(256, $unit->toBytes());
    }

    /**
     * Tests if the method `create` throws the correct exception when passed a negative integer.
     */
    #[Test]
    public function createThrowsExceptionForNegativeBytes(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ByteUnit::create(-1);
    }

    #[Test]
    public function toKilobytes(): void
    {
        self::assertEqualsWithDelta(1.0, ByteUnit::create(1024)->toKilobytes(), 0);
    }

    #[Test]
    public function toMegabytes(): void
    {
        self::assertEqualsWithDelta(1.0, ByteUnit::create(1024 * 1024)->toMegabytes(), 0);
    }
}
