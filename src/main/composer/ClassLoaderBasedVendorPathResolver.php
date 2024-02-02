<?php

declare(strict_types=1);

namespace vinyl\std\composer;

use ReflectionClass;
use RuntimeException;
use vinyl\std\io\FileSystems;
use vinyl\std\io\Path;
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
    public function resolve(): Path
    {
        $classLoaderClassName = 'Composer\Autoload\ClassLoader';
        if (!class_exists($classLoaderClassName)) {
            throw new RuntimeException('Composer not initialized, please execute: "composer install" command.');
        }

        $dir = dirname((new ReflectionClass($classLoaderClassName))->getFileName(), 2);

        return FileSystems::default()->path($dir);
    }
}
