<?php

declare(strict_types=1);

namespace vinyl\std\lang;

use InvalidArgumentException;
use Throwable;
use function assert;
use function interface_exists;
use function is_int;
use function ltrim;

/**
 * @extends Entry<interface-string>
 */
final class InterfaceObject extends Entry
{
    /**
     * @param interface-string $interfaceName
     */
    private function __construct(string $interfaceName)
    {
        $this->name = ltrim($interfaceName, '\\');
    }

    /**
     * Returns new {@see \vinyl\std\lang\InterfaceObject} for given interface name
     *
     * @throws \InvalidArgumentException if given interface not exists
     */
    public static function create(string $interfaceName): self
    {
        if ($interfaceName === '') {
            throw new InvalidArgumentException('Interface name could not be empty.');
        }

        try {
            if (interface_exists($interfaceName)) {
                return new self($interfaceName);
            }
        } catch (Throwable $e) {
            $code = $e->getCode();
            assert(is_int($code));
            throw new InvalidArgumentException("Interface [{$interfaceName}] does not exists.", $code, $e);
        }

        throw new InvalidArgumentException("Interface [{$interfaceName}] does not exists.");
    }

    /**
     * Returns new {@see \vinyl\std\lang\InterfaceObject} for given interface name or null if interface not exists
     *
     * @throws \InvalidArgumentException if given interface is empty
     */
    public static function tryCreate(string $interfaceName): ?self
    {
        if ($interfaceName === '') {
            return null;
        }

        try {
            if (!interface_exists($interfaceName)) {
                return null;
            }
        } catch (Throwable $e) {
            return null;
        }

        return new self($interfaceName);
    }
}
