<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Class MapNode
 *
 * @template TKey of string|int|null|bool|object
 * @template TValue
 */
final class MapPair
{
    /**
     * @psalm-var TKey
     */
    public $key;

    /**
     * @psalm-var TValue
     */
    public $value;

    /**
     * MapPair constructor.
     *
     * @psalm-param TKey   $key
     * @psalm-param TValue $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
