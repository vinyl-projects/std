<?php

declare(strict_types=1);

namespace vinyl\std\io\win;

use vinyl\std\io\FileSystem;
use vinyl\std\io\Path;
use function count;
use function implode;

class WinFileSystem extends FileSystem
{
    private const WIN_FILESYSTEM_SEPARATOR = '\\';

    public function separator(): string
    {
        return self::WIN_FILESYSTEM_SEPARATOR;
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
            return new WinPath($this, $first);
        }

        $resultPath = $first
            . self::WIN_FILESYSTEM_SEPARATOR
            . implode(self::WIN_FILESYSTEM_SEPARATOR, $more);

        return new WinPath($this, $resultPath);
    }
}
