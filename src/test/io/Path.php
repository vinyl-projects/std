<?php

declare(strict_types=1);

namespace vinyl\stdTest\io;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

abstract class Path extends TestCase
{
    #[Test]
    #[DataProvider('absolutePathProvider')]
    public function isAbsolute(string $path, bool $isAbsolute): void
    {
        self::assertSame(\vinyl\std\io\Path::of($path)->isAbsolute(), $isAbsolute);
    }
    
    /**
     * @return array<array{0:string, 1:bool}>
     */
    abstract public static function absolutePathProvider(): array;
}
