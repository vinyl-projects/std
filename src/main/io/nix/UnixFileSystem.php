<?php

declare(strict_types=1);

namespace vinyl\std\io\nix;

use vinyl\std\io\FileSystem;
use vinyl\std\io\Path;
use function count;
use function implode;

class UnixFileSystem extends FileSystem
{
    private const UNIX_PATH_SEPARATOR = '/';

    public function separator(): string
    {
        return self::UNIX_PATH_SEPARATOR;
    }

    public function isOpen(): bool
    {
        return true;
    }

    public function isReadonly(): bool
    {
        return false;
    }

    public function path(string $first, string ...$more): Path
    {
        if (count($more) === 0) {
            return new UnixPath($this, $first);
        }

        $resultPath = $first . self::UNIX_PATH_SEPARATOR . implode(self::UNIX_PATH_SEPARATOR, $more);

        return new UnixPath($this, $resultPath);
    }
}
