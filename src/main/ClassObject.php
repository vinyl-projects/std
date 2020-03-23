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
     * @throws \InvalidArgumentException if given class not exists
     */
    public function __construct(string $className)
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

        $this->className = $className;
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
     * @return array<string, string>
     */
    public function toParentMap(): array
    {
        return class_parents($this->className);
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
