<?php

declare(strict_types=1);

namespace vinyl\std;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function class_exists;
use function class_implements;
use function class_parents;
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
        try {
            if (!class_exists($className)) {
                throw new InvalidArgumentException("Class [{$className}] not exists.");
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "An exception occurred during class loading. Class: [{$className}] Details: {$e->getMessage()}",
                0,
                $e
            );
        }

        return new self(ltrim($className, '\\'));
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
     * Return the interfaces which are implemented by current class
     *
     * @return array<string, string>
     */
    public function toInterfaceMap(): array
    {
        return class_implements($this->className);
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
}
