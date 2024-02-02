<?php

declare(strict_types=1);

namespace vinyl\std\io;

/**
 * @template T of object
 */
interface FileAttribute
{
    /**
     *
     * Returns the attribute name
     * @return string Attribute name
     */
    public function name(): string;

    /**
     * Returns the attribute value
     *
     * @return T Attribute value
     */
    public function value();
}
