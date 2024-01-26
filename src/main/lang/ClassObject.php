<?php

declare(strict_types=1);

namespace vinyl\std\lang;

use InvalidArgumentException;
use Throwable;
use vinyl\std\lang\collections\ArrayVector;
use vinyl\std\lang\collections\Vector;
use function assert;
use function class_exists;
use function class_parents;
use function is_int;
use function ltrim;

/**
 * Class ClassObject
 *
 * @extends Entry<class-string>
 */
final class ClassObject extends Entry
{
    /**
     * ClassObject constructor.
     *
     * @param class-string $className
     */
    private function __construct(string $className)
    {
        $this->name = ltrim($className, '\\');
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

        try {
            if (class_exists($className)) {
                return new self($className);
            }
        } catch (Throwable $e) {
            $code = $e->getCode();
            assert(is_int($code));
            throw new InvalidArgumentException("Class [{$className}] does not exists.", $code, $e);
        }

        throw new InvalidArgumentException("Class [{$className}] does not exists.");
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
            return null;
        }

        try {
            if (!class_exists($className)) {
                return null;
            }
        } catch (Throwable $e) {
            return null;
        }

        return new self($className);
    }

    private static function throwIfClassNameStartsWithBackslash(string $className): void
    {
        if ($className[0] === '\\') {
            throw new InvalidArgumentException('Class name could not be started from backslash.');
        }
    }

    /**
     * Returns the parent classes of the current class
     *
     * @return \vinyl\std\lang\collections\Vector<\vinyl\std\lang\ClassObject>
     */
    public function toParentClassObjectVector(): Vector
    {
        $parents = [];
        foreach (class_parents($this->name) as $classParent) {
            $parents[] = new self($classParent);
        }

        return new ArrayVector($parents);
    }
}
