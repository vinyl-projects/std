<?php

declare(strict_types=1);

namespace vinyl\std\composer;

use Error;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use function class_exists;
use function dirname;

/**
 * Class ClassLoaderBasedVendorPathResolver
 */
final class ClassLoaderBasedVendorPathResolver implements VendorPathResolver
{
    /**
     * {@inheritDoc}
     */
    public function resolve(): string
    {
        $classLoaderClassName = 'Composer\Autoload\ClassLoader';
        if (!class_exists($classLoaderClassName)) {
            throw new RuntimeException('Composer not initialized, please execute: "composer install" command.');
        }

        try {
            return dirname((new ReflectionClass($classLoaderClassName))->getFileName(), 2);
        } catch (ReflectionException $e) {
            throw new Error($e->getMessage(), 0, $e);
        }
    }
}
