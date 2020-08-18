<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use vinyl\std\lang\collections\ArrayVector;
use vinyl\std\lang\collections\Vector;

/**
 * Class ReadonlyArrayVectorTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ReadonlyArrayVectorTest extends ReadonlyVectorTest
{
    /**
     * {@inheritDoc}
     */
    protected static function createList(...$elements): Vector
    {
        return new ArrayVector($elements);
    }
}
