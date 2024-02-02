<?php

declare(strict_types=1);

namespace vinyl\std\io;

use Stringable;
use function is_object;
use function realpath;
use function str_ends_with;
use function str_starts_with;

/**
 * The Path interface represents a file path or a directory path.
 */
abstract class Path implements Stringable
{
    public function __construct(protected FileSystem $fileSystem, protected string $path)
    {
    }

    public static function of(string $first, string ...$more): Path
    {
        return FileSystems::default()->path($first, ...$more);
    }

    /**
     * Checks whether a given path is an absolute path.
     *
     * @return bool Returns true if the path is an absolute path, otherwise returns false.
     */
    abstract public function isAbsolute(): bool;

    /**
     * Retrieves the FileSystem object.
     *
     * @return FileSystem The FileSystem object.
     */
    public function fileSystem(): FileSystem
    {
        return $this->fileSystem;
    }

    public function startsWith(string|Path $other): bool
    {
        if (is_object($other) && !$other instanceof static) {
            return false;
        }

        return str_starts_with($this->path, (string)$other);
    }

    public function endsWith(string|Path $other): bool
    {
        if (is_object($other) && !$other instanceof static) {
            return false;
        }

        return str_ends_with($this->path, (string)$other);
    }

    public function toRealPath(): Path
    {
        $realpath = realpath($this->path);

        if ($realpath === false) {
            throw new \RuntimeException('Not implemented.');
        }

        return $this->fileSystem->path($realpath);
    }

    abstract public function toAbsolutePath(): Path;

    public function toString(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
