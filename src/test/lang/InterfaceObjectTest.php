<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use vinyl\std\lang\InterfaceObject;

class InterfaceObjectTest extends TestCase
{
    #[Test]
    public function createWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Interface name could not be empty.');
        InterfaceObject::create('');
    }

    #[Test]
    public function createWithNonExistInterface(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Interface [Throwable_NotExists] does not exists.');
        InterfaceObject::create('Throwable_NotExists');
    }

    #[Test]
    public function tryCreateWithEmptyString(): void
    {
        $result = InterfaceObject::tryCreate('');
        self::assertNull($result);
    }

    #[Test]
    public function tryCreateWithNonExistInterface(): void
    {
        $result = InterfaceObject::tryCreate('Throwable_NotExists');
        self::assertNull($result);
    }

    #[Test]
    public function create(): void
    {
        $obj = InterfaceObject::create(\Throwable::class);
        self::assertInstanceOf(InterfaceObject::class, $obj);
    }

    #[Test]
    public function nameReturn(): void
    {
        $obj = InterfaceObject::create(\Throwable::class);
        self::assertSame(\Throwable::class, $obj->name());
    }
}
