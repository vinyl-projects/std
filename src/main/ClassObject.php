<?php

declare(strict_types=1);

namespace vinyl\std;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function array_values;
use function class_exists;
use function class_implements;
use function class_parents;
use function get_class;
use function ltrim;

/**
 * Class ClassObject
 */
final class ClassObject
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
     * Returns new {@see \vinyl\std\ClassObject} for given class name
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
                throw new InvalidArgumentException("Class [{$className}] not exists.");
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException("Class [{$className}] not exists.", $e->getCode(), $e);
        }

        return new self($className);
    }

    /**
     * Returns new {@see \vinyl\std\ClassObject} for given object
     */
    public static function createFromObject(object $object): self
    {
        #todo in PHP8 will be possible to use $object::class, see: https://wiki.php.net/rfc/class_name_literal_on_object
        return new self(get_class($object));
    }

    /**
     * Returns class name
     *
     * @return class-string
     */
    public function className(): string
    {
        return $this->className;
    }

    /**
     *  Return the parent classes of the current class
     *
     * @return \vinyl\std\ClassObject[]
     */
    public function toParentClassObjectList(): array
    {
        $parentList = [];
        foreach (class_parents($this->className) as $classParent) {
            $parentList[] = new self($classParent);
        }

        return $parentList;
    }

    /**
     * Returns the interfaces which are implemented by current class
     *
     * @return string[]
     */
    public function toInterfaceList(): array
    {
        return array_values(class_implements($this->className));
    }

    /**
     * Returns {@see ReflectionClass} object
     */
    public function toReflectionClass(): ReflectionClass
    {
        try {
            return new ReflectionClass($this->className);
        } catch (ReflectionException $e) {
            throw new LogicException($e->getMessage(), 0, $e);
        }
    }

    private static function throwIfClassNameStartsWithBackslash(string $className): void
    {
        if ($className[0] === '\\') {
            throw new InvalidArgumentException('Class name could not be started from backslash.');
        }
    }
}
