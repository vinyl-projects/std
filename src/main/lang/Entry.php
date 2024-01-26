<?php

declare(strict_types=1);

namespace vinyl\std\lang;

use Error;
use ReflectionClass;
use ReflectionException;
use Stringable;
use vinyl\std\lang\collections\Identifiable;

/**
 * @template T as class-string|interface-string
 */
abstract class Entry implements Identifiable, Stringable
{
    /**
     * @var T
     */
    protected string $name;

    /**
     * @return T
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function identity(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Returns {@see ReflectionClass} object
     *
     * return \ReflectionClass<T>
     */
    public function toReflectionClass(): ReflectionClass
    {
        try {
            return new ReflectionClass($this->name);
        } catch (ReflectionException $e) {
            throw new Error($e->getMessage(), 0, $e);
        }
    }
}
