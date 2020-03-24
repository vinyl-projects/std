<?php

declare(strict_types=1);

namespace vinyl\stdTest;

use Exception;
use InvalidArgumentException;
use ReflectionClass;
use vinyl\std\ClassObject;
use PHPUnit\Framework\TestCase;
use function array_map;
use function get_class;
use function spl_autoload_register;

/**
 * Class ClassObjectTest
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

        self::assertEquals(get_class($class), $classObject->className());
    }

    /**
     * @test
     */
    public function constructorThrowsExceptionIfGivenClassNotExists(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ClassObject::create('vinyl\stdTest\ClassNotExists');
    }

    public function constructorThrowsExceptionIfAutoloaderThrowsError(): void
    {
        spl_autoload_register(static function (): void {
            throw new Exception('Test');
        });
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('An exception occurred during class loading. Class: [vinyl\stdTest\ClassNotExists] Details: Test');
        ClassObject::create('vinyl\stdTest\ClassNotExists');
    }

    /**
     * @test
     */
    public function toParentMap(): void
    {
        $class = new class extends InvalidArgumentException {
        };
        $classObject = ClassObject::create(get_class($class));

        $expectedParents = [
            'InvalidArgumentException',
            'LogicException',
            'Exception',
        ];

        self::assertEquals(
            $expectedParents,
            array_map(static fn(ClassObject $classObject) => $classObject->className(), $classObject->toParentClassObjectList())
        );
    }

    /**
     * @test
     */
    public function toInterfaceMap(): void
    {
        $class = new class extends Exception {
        };
        $classObject = ClassObject::create(get_class($class));

        $expectedParents = [
            'Throwable' => 'Throwable',
        ];

        self::assertEquals($expectedParents, $classObject->toInterfaceMap());
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
