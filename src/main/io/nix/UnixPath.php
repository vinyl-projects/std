<?php

declare(strict_types=1);

namespace vinyl\std\io\nix;

use vinyl\std\io\Path;
use function strlen;

class UnixPath extends Path
{
    public function isAbsolute(): bool
    {
        return $this->path !== '' && $this->path[0] === '/';
    }

    public function toAbsolutePath(): Path
    {
        if ($this->isAbsolute()) {
            return $this;
        }

        return new self(
            $this->fileSystem,
            self::resolveDirectory($this->fileSystem->defaultDirectory(), $this->path)
        );
    }

    protected static function resolveDirectory(string $basePath, string $child): string
    {
        if ($child === '') {
            return $basePath;
        }

        if ($basePath === '' || $child[0] === '/') {
            return $child;
        }

        if (strlen($basePath) === 1 && $basePath[0] === '/') {
            return '/' . $child;
        }

        return $basePath . '/' . $child;
    }
}
