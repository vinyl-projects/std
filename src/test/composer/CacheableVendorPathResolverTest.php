<?php

declare(strict_types=1);

namespace vinyl\stdTest\composer;

use PHPUnit\Framework\TestCase;
use vinyl\std\composer\CacheableVendorPathResolver;
use vinyl\std\composer\VendorPathResolver;
use vinyl\std\io\FileSystems;

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
        $path = FileSystems::default()->path('hello/world');
        $mock = $this->createMock(VendorPathResolver::class);
        $mock->expects(self::once())
            ->method('resolve')
            ->willReturn($path);

        $resolver = new CacheableVendorPathResolver($mock);

        self::assertSame($path->toString(), $resolver->resolve()->toString());
        self::assertSame($path, $resolver->resolve());
        self::assertSame($path, $resolver->resolve());
    }
}
