<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use vinyl\std\lang\collections\MutableArrayVector;
use vinyl\std\lang\collections\MutableVector;
use function array_values;

/**
 * Class MutableArrayVectorTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MutableArrayVectorTest extends TestMutableVector
{
    /**
     * {@inheritDoc}
     */
    protected static function createList(...$elements): MutableVector
    {
        return new MutableArrayVector(array_values($elements));
    }
}
