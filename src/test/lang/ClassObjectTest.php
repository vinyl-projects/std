<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang;

use Exception;
use InvalidArgumentException;
use ReflectionClass;
use vinyl\std\lang\ClassObject;
use PHPUnit\Framework\TestCase;
use function array_map;
use function get_class;
use function spl_autoload_register;

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
     */
    public function exceptionIsThrownIfClassNameContainsBackslash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class name could not be started from backslash.');
        ClassObject::create('\\');
    }

    /**
     * @test
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
        $object = new class {};
        self::assertEquals(get_class($object), ClassObject::createFromObject($object)->name());
    }

    /**
     * @test
     */
    public function tryCreateThrowsExceptionIfGivenClassIsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class name could not be empty.');
        ClassObject::tryCreate('');
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
     */
    public function tryCreateReturnsNullIfAutoloaderThrowsException(): void
    {
        spl_autoload_register(static function (): void {
            throw new Exception('Test');
        });
        self::assertNull(ClassObject::tryCreate('vinyl\stdTest\ClassNotExists'));
    }

    /**
     * @test
     */
    public function instantiateClassObjectThroughTryCreateMethod(): void
    {
        $object = new class {};
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

        $actualParents = [];

        foreach ($classObject->toParentClassObjectVector() as $item) {
            $actualParents[] = $item->name();
        }

        self::assertEquals($expectedParents, $actualParents);
    }

    /**
     * @test
     */
    public function toInterfaceNameVector(): void
    {
        $class = new class extends Exception {
        };
        $classObject = ClassObject::create(get_class($class));

        $expectedInterfaces = ['Throwable'];
        $actualInterfaces = [];

        foreach ($classObject->toInterfaceNameVector() as $item) {
            $actualInterfaces[] = $item;
        }

        self::assertSame($expectedInterfaces, $actualInterfaces);
    }

    /**
     * @test
     */
    public function toReflectionClass(): void
    {
        $class = new class {
        };
        $classObject = ClassObject::create(get_class($class));

        self::assertInstanceOf(ReflectionClass::class, $classObject->toReflectionClass());
    }
}
