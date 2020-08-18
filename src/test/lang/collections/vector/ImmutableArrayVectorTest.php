<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\collections\vector;

use vinyl\std\lang\collections\ImmutableArrayVector;
use vinyl\std\lang\collections\ImmutableVector;
use function vinyl\std\lang\collections\immutableVectorFromArray;

/**
 * Class ImmutableArrayVectorTest
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ImmutableArrayVectorTest extends ImmutableVectorTest
{
    /**
     * {@inheritDoc}
     */
    protected static function createList(...$elements): ImmutableVector
    {
        return immutableVectorFromArray($elements);
    }
}
