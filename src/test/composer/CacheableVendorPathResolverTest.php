<?php

declare(strict_types=1);

namespace vinyl\stdTest\composer;

use PHPUnit\Framework\TestCase;
use vinyl\std\composer\CacheableVendorPathResolver;
use vinyl\std\composer\VendorPathResolver;

/**
 * Class CacheableVendorPathResolverTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CacheableVendorPathResolverTest extends TestCase
{
    /**
     * @test
     */
    public function resolve(): void
    {
        $mock = $this->createMock(VendorPathResolver::class);
        $mock->expects(self::once())
            ->method('resolve')
            ->willReturn('hello/world');

        $resolver = new CacheableVendorPathResolver($mock);

        self::assertSame('hello/world', $resolver->resolve());
        self::assertSame('hello/world', $resolver->resolve());
    }
}
