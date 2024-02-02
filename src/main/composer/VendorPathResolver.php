<?php

declare(strict_types=1);

namespace vinyl\std\composer;

use vinyl\std\io\Path;

/**
 * Interface VendorPathResolver
 */
interface VendorPathResolver
{
    /**
     * Returns absolute path to vendor directory
     */
    public function resolve(): Path;
}
