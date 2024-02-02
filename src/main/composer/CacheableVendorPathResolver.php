<?php

declare(strict_types=1);

namespace vinyl\std\composer;

use vinyl\std\io\Path;

/**
 * Class CacheableVendorPathResolver
 */
final class CacheableVendorPathResolver implements VendorPathResolver
{
    private VendorPathResolver $resolver;

    private ?Path $resolvedVendorPath = null;

    /**
     * CacheableVendorPathResolver constructor.
     */
    public function __construct(?VendorPathResolver $vendorPathResolver = null)
    {
        $this->resolver = $vendorPathResolver ?? new ClassLoaderBasedVendorPathResolver();
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(): Path
    {
        if ($this->resolvedVendorPath === null) {
            $this->resolvedVendorPath = $this->resolver->resolve();
        }

        return $this->resolvedVendorPath;
    }
}
