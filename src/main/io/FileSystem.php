<?php

declare(strict_types=1);

namespace vinyl\std\io;

abstract class FileSystem
{
    public function __construct(protected FileSystemBackend $fileSystemBackend, protected string $defaultDirectory)
    {
    }

    abstract public function separator(): string;

    abstract public function isOpen(): bool;

    abstract public function isReadonly(): bool;

    abstract public function path(string $first, string ...$more): Path;

    public function backend(): FileSystemBackend
    {
        return $this->fileSystemBackend;
    }

    public function defaultDirectory(): string
    {
        return $this->defaultDirectory;
    }
}
