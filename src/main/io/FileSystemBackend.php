<?php

declare(strict_types=1);

namespace vinyl\std\io;

use vinyl\std\lang\collections\ReadonlyMap;
use vinyl\std\net\URI;

/**
 * Class FileSystemBackend
 *
 * This class is a base backend for all file system interactions.
 */
abstract class FileSystemBackend
{
    /**
     * Get the scheme used by this file system backend.
     *
     * The exact format and semantics of the scheme are backend-specific.
     *
     * @return string the scheme used by this file system backend.
     */
    abstract public function scheme(): string;

    /**
     * Creates a new FileSystem instance based on the specified URI and attributes.
     *
     * This method is used to create new file system instances. The exact attributes required
     * and how they are interpreted are backend-specific.
     *
     * @param URI                             $uri The URI to be used for the new file system.
     * @param ReadonlyMap<string, mixed>|null $attributes The attributes to be used for the new file system.
     *
     * @return \vinyl\std\io\FileSystem The newly created file system.
     */
    abstract public function newFileSystemFromUri(URI $uri, ?ReadonlyMap $attributes = null): FileSystem;

    /**
     * Find the appropriate file system for the given URI.
     *
     * @param URI $uri The URI for which to find the file system.
     *
     * @return FileSystem|null The appropriate file system, or null if no applicable file system is found.
     */
    abstract public function findFileSystem(URI $uri): ?FileSystem;

    /**
     * Create a new file system based on the given file path and attributes.
     *
     * @param Path                            $path The file path used to create the file system.
     * @param ReadonlyMap<string, mixed>|null $attributes The attributes associated with the file system.
     *
     * @return FileSystem The new file system created with the provided path and attributes.
     */
    abstract public function newFileSystem(Path $path, $attributes = null): FileSystem;

    abstract public function createDirectory(Path $path, $attributes = null): void;

    abstract public function createSymbolicLink(Path $link, Path $target, $attributes = null): void;

    abstract public function delete(Path $path): void;

    abstract public function deleteIfExist(Path $path): bool;

    abstract public function copy(Path $source, Path $target, $options): void; //todo add options

    abstract public function move(Path $source, Path $target, $options): void; //todo add options

    abstract public function isHidden(Path $path): bool;

    abstract public function exists(Path $path): bool;
}
