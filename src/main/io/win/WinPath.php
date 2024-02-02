<?php

declare(strict_types=1);

namespace vinyl\std\io\win;

use vinyl\std\io\Path;

class WinPath extends Path
{
    public function isAbsolute(): bool
    {
        throw new \RuntimeException('Not implemented.');
    }

    public function toAbsolutePath(): Path
    {
        if ($this->isAbsolute()) {
            return $this;
        }

        throw new \RuntimeException('Not implemented.');
    }
}
