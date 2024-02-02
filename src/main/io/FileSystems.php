<?php

declare(strict_types=1);

namespace vinyl\std\io;

use vinyl\std\net\URI;
use function assert;

final class FileSystems
{
    private static ?FileSystem $defaultFilesystem = null;

    public static function default(): FileSystem
    {
        if (self::$defaultFilesystem === null) {
            $localFileSystemBackend = new LocalFileSystemBackend();
            $fileSystem = $localFileSystemBackend->findFileSystem(URI::create('file:///'));
            assert($fileSystem instanceof FileSystem);
            self::$defaultFilesystem = $fileSystem;
        }

        return self::$defaultFilesystem;
    }
}
