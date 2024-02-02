<?php

declare(strict_types=1);

namespace vinyl\stdTest\io;

use PHPUnit\Framework\Attributes\Test;
use vinyl\std\io\nix\UnixPath;

class UnixPathTest extends Path
{
    #[Test]
    public function creation(): void
    {
        $path = \vinyl\std\io\Path::of('');
        self::assertInstanceOf(UnixPath::class, $path);
    }

    /**
     * {@inheritDoc}
     */
    public static function absolutePathProvider(): array
    {
        return [
            ['/', true],
            ['/foo', true],
            ['/foo/', true],
            ['/foo/bar', true],
            ['/foo/bar/', true],
            ['foo', false],
            ['foo/', false],
            ['foo/bar', false],
            ['foo/bar/', false],
        ];
    }
}
