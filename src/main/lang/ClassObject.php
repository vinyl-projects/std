<?php

declare(strict_types=1);

namespace vinyl\std\lang;

use Error;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Stringable;
use Throwable;
use vinyl\std\lang\collections\ArrayVector;
use vinyl\std\lang\collections\Identifiable;
use vinyl\std\lang\collections\Vector;
use function assert;
use function class_exists;
use function class_implements;
use function class_parents;
use function is_int;
use function vinyl\std\lang\collections\vectorFromArray;

/**
 * Class ClassObject
 */
final class ClassObject implements Identifiable, Stringable
{
    /** @var class-string */
    private string $className;

    /**
     * ClassObject constructor.
     *
     * @param class-string $className
     */
    private function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * Returns new {@see \vinyl\std\lang\ClassObject} for given class name
     *
     * @throws \InvalidArgumentException if given class not exists
     */
    public static function create(string $className): self
    {
        if ($className === '') {
            throw new InvalidArgumentException('Class name could not be empty.');
        }

        self::throwIfClassNameStartsWithBackslash($className);
        try {
            if (!class_exists($className)) {
                throw new InvalidArgumentException("Class [{$className}] does not exists.");
            }
        } catch (Throwable $e) {
            $code = $e->getCode();
            assert(is_int($code));
            throw new InvalidArgumentException("Class [{$className}] does not exists.", $code, $e);
        }

        return new self($className);
    }

    /**
     * Returns new {@see \vinyl\std\lang\ClassObject} for given object
     */
    public static function createFromObject(object $object): self
    {
        return new self($object::class);
    }

    /**
     * Returns new {@see \vinyl\std\lang\ClassObject} for given class name or null if class not exists
     *
     * @throws \InvalidArgumentException if given className is empty
     */
    public static function tryCreate(string $className): ?self
    {
        if ($className === '') {
            throw new InvalidArgumentException('Class name could not be empty.');
        }

        self::throwIfClassNameStartsWithBackslash($className);

        try {
            if (!class_exists($className)) {
                return null;
            }
        } catch (Throwable $e) {
            return null;
        }

        return new self($className);
    }

    /**
     * Returns class name
     *
     * @return class-string
     */
    public function name(): string
    {
        return $this->className;
    }

    /**
     * Returns the parent classes of the current class
     *
     * @return \vinyl\std\lang\collections\Vector<\vinyl\std\lang\ClassObject>
     */
    public function toParentClassObjectVector(): Vector
    {
        $parents = [];
        foreach (class_parents($this->className) as $classParent) {
            $parents[] = new self($classParent);
        }

        return new ArrayVector($parents);
    }

    /**
     * Returns the interfaces which are implemented by current class
     *
     * @return \vinyl\std\lang\collections\Vector<string>
     */
    public function toInterfaceNameVector(): Vector
    {
        $interfaces = class_implements($this->className);

        assert($interfaces !== false);

        return vectorFromArray($interfaces);
    }

    /**
     * Returns {@see ReflectionClass} object
     */
    public function toReflectionClass(): ReflectionClass
    {
        try {
            return new ReflectionClass($this->className);
        } catch (ReflectionException $e) {
            throw new Error($e->getMessage(), 0, $e);
        }
    }

    private static function throwIfClassNameStartsWithBackslash(string $className): void
    {
        if ($className[0] === '\\') {
            throw new InvalidArgumentException('Class name could not be started from backslash.');
        }
    }

    /**
     * @inheritDoc
     */
    public function identity(): string
    {
        return $this->className;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->className;
    }
}
