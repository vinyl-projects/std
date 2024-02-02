<?php

declare(strict_types=1);

namespace vinyl\std\io;

use vinyl\std\io\nix\UnixFileSystem;
use vinyl\std\io\win\WinFileSystem;
use vinyl\std\lang\collections\ReadonlyMap;
use vinyl\std\net\URI;
use function file_exists;
use function getcwd;
use const PHP_OS_FAMILY;

final class LocalFileSystemBackend extends FileSystemBackend
{
    private FileSystem $fileSystem;

    public function __construct()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->fileSystem = new UnixFileSystem($this, getcwd());
        } else {
            $this->fileSystem = new WinFileSystem($this, getcwd());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function scheme(): string
    {
        return 'file';
    }

    /**
     * {@inheritDoc}
     */
    public function newFileSystemFromUri(URI $uri, ?ReadonlyMap $attributes = null): FileSystem
    {
        if ($uri->scheme() !== $this->scheme()) {
            throw new \RuntimeException('Not implemented.');
        }

        return $this->fileSystem;
    }

    /**
     * {@inheritDoc}
     */
    public function findFileSystem(URI $uri): ?FileSystem
    {
        if ($uri->scheme() !== $this->scheme()) {
            throw new \RuntimeException('Not implemented.');
        }

        return $this->fileSystem;
    }

    /**
     * {@inheritDoc}
     */
    public function newFileSystem(Path $path, $attributes = null): FileSystem
    {
        if ($path->fileSystem()->backend()->scheme() !== $this->scheme()) {
            throw new \RuntimeException('Not implemented.');
        }

        return $this->fileSystem;
    }

    /**
     * {@inheritDoc}
     */
    public function createDirectory(Path $path, $attributes = null): void
    {
        $directoryPath = $path->toString();
        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true) && !is_dir($directoryPath)) {
                throw new \Exception('Failed to create directory: ' . $directoryPath);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createSymbolicLink(Path $link, Path $target, $attributes = null): void
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Path $path): void
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function deleteIfExist(Path $path): bool
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function copy(Path $source, Path $target, $options): void
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function move(Path $source, Path $target, $options): void
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function isHidden(Path $path): bool
    {
        throw new \RuntimeException('Not implemented.');
    }

    /**
     * {@inheritDoc}
     */
    public function exists(Path $path): bool
    {
        return file_exists($path->toString());
    }
}
