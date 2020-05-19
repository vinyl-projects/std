<?php

declare(strict_types=1);

namespace vinyl\std\composer;

/**
 * Class CacheableVendorPathResolver
 */
final class CacheableVendorPathResolver implements VendorPathResolver
{
    private VendorPathResolver $resolver;

    private ?string $resolvedVendorPath = null;

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
    public function resolve(): string
    {
        if ($this->resolvedVendorPath === null) {
            $this->resolvedVendorPath = $this->resolver->resolve();
        }

        return $this->resolvedVendorPath;
    }
}
