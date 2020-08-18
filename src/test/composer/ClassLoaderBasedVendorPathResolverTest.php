<?php

declare(strict_types=1);

namespace vinyl\stdTest\composer;

use PHPUnit\Framework\TestCase;
use vinyl\std\composer\ClassLoaderBasedVendorPathResolver;
use function dirname;
use const DIRECTORY_SEPARATOR;

/**
 * Class ClassLoaderBasedVendorPathResolverTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ClassLoaderBasedVendorPathResolverTest extends TestCase
{
    /**
     * @test
     */
    public function resolve(): void
    {
        $resolver = new ClassLoaderBasedVendorPathResolver();
        $vendorDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor';

        self::assertSame($vendorDir, $resolver->resolve());
    }
}
