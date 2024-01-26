<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use vinyl\std\lang\ClassObject;
use function get_class;
use function spl_autoload_register;
use function spl_autoload_unregister;

/**
 * Class ClassObjectTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ClassObjectTest extends TestCase
{
    /**
     * @test
     */
    public function className(): void
    {
        $class = new class {
        };
        $classObject = ClassObject::create(get_class($class));

        self::assertEquals(get_class($class), $classObject->name());
    }

    /**
     * @test
     */
    public function constructorThrowsExceptionIfGivenClassNotExists(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ClassObject::create('vinyl\stdTest\ClassNotExists');
    }

    /**
     * @test
     */
    public function createThrowsExceptionIfGivenClassIsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class name could not be empty.');
        ClassObject::create('');
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function expectedExceptionIsThrownIfAutoloaderThrowsAnException(): void
    {
        spl_autoload_register(static function (): void {
            throw new Exception('Test');
        });
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class [vinyl\stdTest\ClassNotExists] does not exists.');
        ClassObject::create('vinyl\stdTest\ClassNotExists');
    }

    /**
     * @test
     */
    public function exceptionIsThrownIfClassNotExists(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class [vinyl\stdTest\ClassNotExists] does not exists.');
        ClassObject::create('vinyl\stdTest\ClassNotExists');
    }

    /**
     * @test
     */
    public function instantiateClassObjectFromObject(): void
    {
        $object = new class {
        };
        self::assertEquals(get_class($object), ClassObject::createFromObject($object)->name());
    }

    /**
     * @test
     */
    public function tryCreateThrowsExceptionIfGivenClassIsEmptyString(): void
    {
        $result = ClassObject::tryCreate('');
        self::assertNull($result);
    }

    /**
     * @test
     */
    public function tryCreateReturnsNullIfClassNotExists(): void
    {
        self::assertNull(ClassObject::tryCreate('vinyl\stdTest\ClassNotExists'));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function tryCreateReturnsNullIfAutoloaderThrowsException(): void
    {
        $closure = static function (): void {
            throw new Exception('Test');
        };
        spl_autoload_register($closure);
        self::assertNull(ClassObject::tryCreate('vinyl\stdTest\ClassNotExists'));
        spl_autoload_unregister($closure);
    }

    /**
     * @test
     */
    public function instantiateClassObjectThroughTryCreateMethod(): void
    {
        $object = new class {
        };
        self::assertNotNull(ClassObject::tryCreate(get_class($object)));
    }

    /**
     * @test
     */
    public function tryCreateMethodReturnsNullIfGivenClassNotExists(): void
    {
        self::assertNull(ClassObject::tryCreate('vinyl\stdTest\ClassNotExists'));
    }

    /**
     * @test
     */
    public function toParentClassObjectVector(): void
    {
        $class = new class extends InvalidArgumentException {
        };
        $classObject = ClassObject::create(get_class($class));

        $expectedParents = [
            'InvalidArgumentException',
            'LogicException',
            'Exception',
        ];

        $actualParents = $classObject
            ->toParentClassObjectVector()
            ->map(fn(ClassObject $classObject): string => $classObject->name())
            ->toArray();

        self::assertEquals($expectedParents, $actualParents);
    }

    /**
     * @test
     */
    public function identityReturnsClassName(): void
    {
        self::assertSame(stdClass::class, ClassObject::createFromObject(new stdClass())->identity());
    }
}
