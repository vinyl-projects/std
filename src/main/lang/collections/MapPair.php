<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Class MapNode
 *
 * @template TKey of string|int|null|bool|object
 * @template-covariant TValue
 */
final class MapPair
{
    /**
     * MapPair constructor.
     *
     * @psalm-param TKey   $key
     * @psalm-param TValue $value
     */
    public function __construct(public $key, public $value)
    {
    }
}
